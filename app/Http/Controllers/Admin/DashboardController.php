<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Lead;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'vehicles_count' => Vehicle::count(),
                'vehicles_sale_count' => Vehicle::where('type', 'sale')->count(),
                'vehicles_rent_count' => Vehicle::where('type', 'rent')->count(),
                'leads_new_count' => Lead::where('status', 'new')->count(),
                'leads_total_count' => Lead::count(),
            ],
            'recent_leads' => Lead::latest()->take(5)->get()
        ]);
    }
}
