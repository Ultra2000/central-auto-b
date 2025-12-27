<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitAvailability;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class VisitAvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = VisitAvailability::where('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return Inertia::render('Admin/VisitAvailabilities/Index', [
            'availabilities' => $availabilities
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_bookings' => 'required|integer|min:1|max:10',
        ]);

        $validated['is_active'] = true;
        $validated['current_bookings'] = 0;

        VisitAvailability::create($validated);

        return redirect()->back()->with('success', 'Disponibilité ajoutée avec succès.');
    }

    public function update(Request $request, VisitAvailability $availability)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_bookings' => 'required|integer|min:1|max:10',
            'is_active' => 'required|boolean',
        ]);

        $availability->update($validated);

        return redirect()->back()->with('success', 'Disponibilité mise à jour.');
    }

    public function destroy(VisitAvailability $availability)
    {
        $availability->delete();
        return redirect()->back()->with('success', 'Disponibilité supprimée.');
    }
}
