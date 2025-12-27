<?php

namespace App\Http\Controllers;

use App\Models\VehicleInterest;
use App\Models\VisitAvailability;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class VehicleInterestController extends Controller
{
    public function getAvailabilities()
    {
        $availabilities = VisitAvailability::where('date', '>=', Carbon::today())
            ->where('is_active', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->filter(function ($availability) {
                return $availability->isAvailable();
            })
            ->map(function ($availability) {
                return [
                    'id' => $availability->id,
                    'date' => $availability->date->format('Y-m-d'),
                    'start_time' => $availability->start_time,
                    'end_time' => $availability->end_time,
                    'available_slots' => $availability->max_bookings - $availability->current_bookings,
                ];
            })
            ->values();

        return response()->json($availabilities);
    }

    public function getDeliveryCost($postalCode)
    {
        $cost = DeliveryZone::findCostByPostalCode($postalCode);
        
        if ($cost !== null) {
            return response()->json([
                'success' => true,
                'cost' => $cost
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Zone de livraison non disponible pour ce code postal'
        ], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'interest_type' => 'required|in:callback,reservation',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable',
            'availability_id' => 'nullable|exists:visit_availabilities,id',
            'message' => 'nullable|string',
            'delivery_option' => 'nullable|in:pickup,delivery',
            'delivery_address' => 'nullable|string|max:255',
            'delivery_city' => 'nullable|string|max:100',
            'delivery_postal_code' => 'nullable|string|max:10',
            'delivery_cost' => 'nullable|numeric|min:0',
        ]);

        $interest = VehicleInterest::create($validated);

        // Si une disponibilité a été sélectionnée, incrémenter le compteur
        if (!empty($validated['availability_id'])) {
            $availability = VisitAvailability::find($validated['availability_id']);
            if ($availability && $availability->isAvailable()) {
                $availability->incrementBookings();
            }
        }

        return back()->with('success', 'Votre demande a été envoyée avec succès !');
    }

    public function index()
    {
        return Inertia::render('Admin/VehicleInterests/Index', [
            'interests' => VehicleInterest::with('vehicle')->latest()->paginate(15),
            'reservations' => VehicleInterest::with('vehicle')
                ->where('interest_type', 'reservation')
                ->whereNotNull('preferred_date')
                ->get()
                ->map(function ($interest) {
                    return [
                        'id' => $interest->id,
                        'title' => $interest->name,
                        'date' => $interest->preferred_date,
                        'time' => $interest->preferred_time,
                        'status' => $interest->status,
                        'vehicle' => $interest->vehicle,
                        'phone' => $interest->phone,
                    ];
                })
        ]);
    }

    public function updateStatus(Request $request, VehicleInterest $interest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,contacted,completed,cancelled'
        ]);

        $interest->update($validated);

        return back();
    }
}
