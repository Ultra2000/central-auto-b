<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeliveryZoneController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/DeliveryZones/Index', [
            'zones' => DeliveryZone::orderBy('cost')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'postal_codes' => 'required|string',
            'cost' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = true;

        DeliveryZone::create($validated);

        return redirect()->back()->with('success', 'Zone de livraison ajoutée avec succès.');
    }

    public function update(Request $request, DeliveryZone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'postal_codes' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $zone->update($validated);

        return redirect()->back()->with('success', 'Zone de livraison mise à jour.');
    }

    public function destroy(DeliveryZone $zone)
    {
        $zone->delete();
        return redirect()->back()->with('success', 'Zone de livraison supprimée.');
    }
}
