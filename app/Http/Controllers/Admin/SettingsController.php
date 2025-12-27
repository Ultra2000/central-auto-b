<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => [
                'plate_price_plexiglass' => Setting::get('plate_price_plexiglass', '14.90'),
                'plate_price_alu' => Setting::get('plate_price_alu', '12.90'),
                'contact_phone' => Setting::get('contact_phone', ''),
                'contact_email' => Setting::get('contact_email', ''),
                'contact_address' => Setting::get('contact_address', ''),
                'opening_hours_week' => Setting::get('opening_hours_week', ''),
                'opening_hours_weekend' => Setting::get('opening_hours_weekend', ''),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'plate_price_plexiglass' => 'required|numeric|min:0',
            'plate_price_alu' => 'required|numeric|min:0',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:255',
            'opening_hours_week' => 'nullable|string|max:255',
            'opening_hours_weekend' => 'nullable|string|max:255',
        ]);

        Setting::set('plate_price_plexiglass', $validated['plate_price_plexiglass']);
        Setting::set('plate_price_alu', $validated['plate_price_alu']);
        Setting::set('contact_phone', $validated['contact_phone']);
        Setting::set('contact_email', $validated['contact_email']);
        Setting::set('contact_address', $validated['contact_address']);
        Setting::set('opening_hours_week', $validated['opening_hours_week']);
        Setting::set('opening_hours_weekend', $validated['opening_hours_weekend']);

        return back()->with('success', 'Paramètres mis à jour avec succès');
    }
}
