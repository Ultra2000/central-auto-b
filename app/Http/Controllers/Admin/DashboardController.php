<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Lead;
use App\Models\PlateOrder;
use App\Models\CustomRequest;
use App\Models\VehicleInterest;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Evolution des demandes (30 derniers jours)
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dates->put(Carbon::now()->subDays($i)->format('Y-m-d'), 0);
        }

        $leadsData = Lead::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->pluck('count', 'date');

        $chartData = $dates->map(function ($count, $date) use ($leadsData) {
            return $leadsData->get($date, 0);
        });

        // 2. Répartition du stock par Marque (Top 5)
        $stockByMake = Vehicle::select('make', DB::raw('count(*) as count'))
            ->groupBy('make')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // 3. Activités Récentes (Merge de plusieurs sources)
        $activities = collect();

        // Nouveaux Véhicules
        $activities = $activities->concat(
            Vehicle::latest()->take(5)->get()->map(fn($item) => [
                'type' => 'vehicle',
                'icon' => 'ph-car',
                'color' => 'text-blue-500 bg-blue-50',
                'title' => 'Nouveau véhicule',
                'description' => $item->make . ' ' . $item->model,
                'date' => $item->created_at
            ])
        );

        // Nouveaux Leads
        $activities = $activities->concat(
            Lead::latest()->take(5)->get()->map(fn($item) => [
                'type' => 'lead',
                'icon' => 'ph-user',
                'color' => 'text-green-500 bg-green-50',
                'title' => 'Nouveau contact',
                'description' => $item->name,
                'date' => $item->created_at
            ])
        );

        // Commandes Plaques
        $activities = $activities->concat(
            PlateOrder::latest()->take(5)->get()->map(fn($item) => [
                'type' => 'plate',
                'icon' => 'ph-article',
                'color' => 'text-purple-500 bg-purple-50',
                'title' => 'Commande de plaque',
                'description' => $item->plate_number,
                'date' => $item->created_at
            ])
        );

        // Demandes Personnalisées
        $activities = $activities->concat(
            CustomRequest::latest()->take(5)->get()->map(fn($item) => [
                'type' => 'custom',
                'icon' => 'ph-sparkle',
                'color' => 'text-orange-500 bg-orange-50',
                'title' => 'Recherche personnalisée',
                'description' => $item->make . ' ' . $item->model,
                'date' => $item->created_at
            ])
        );

        // Intérêts Véhicules
        $activities = $activities->concat(
            VehicleInterest::with('vehicle')->latest()->take(5)->get()->map(fn($item) => [
                'type' => 'interest',
                'icon' => 'ph-heart',
                'color' => 'text-red-500 bg-red-50',
                'title' => 'Intérêt véhicule',
                'description' => $item->vehicle ? ($item->vehicle->make . ' ' . $item->vehicle->model) : 'Véhicule supprimé',
                'date' => $item->created_at
            ])
        );

        $recentActivities = $activities->sortByDesc('date')->take(10)->values();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'vehicles_count' => Vehicle::count(),
                'vehicles_sale_count' => Vehicle::where('type', 'sale')->count(),
                'vehicles_rent_count' => Vehicle::where('type', 'rent')->count(),
                'leads_new_count' => Lead::where('status', 'new')->count(),
                'leads_total_count' => Lead::count(),
            ],
            'charts' => [
                'leads_evolution' => [
                    'labels' => $chartData->keys()->map(fn($d) => Carbon::parse($d)->format('d/m'))->values(),
                    'data' => $chartData->values()
                ],
                'stock_by_make' => [
                    'labels' => $stockByMake->pluck('make'),
                    'data' => $stockByMake->pluck('count')
                ]
            ],
            'recent_activities' => $recentActivities
        ]);
    }
}
