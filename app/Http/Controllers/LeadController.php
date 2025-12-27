<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string',
            'type' => 'required|in:contact,callback,vehicle_inquiry',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'subject' => 'nullable|string'
        ]);

        // Si un sujet est fourni, l'ajouter au message
        if (!empty($validated['subject'])) {
            $subjectText = match($validated['subject']) {
                'info' => 'Demande d\'information',
                'rdv' => 'Prise de rendez-vous',
                'reprise' => 'Estimation reprise',
                'autre' => 'Autre demande',
                default => $validated['subject']
            };
            
            $validated['message'] = "[{$subjectText}] " . ($validated['message'] ?? '');
            unset($validated['subject']);
        }

        Lead::create($validated);

        return back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous recontacterons très prochainement.');
    }
}
