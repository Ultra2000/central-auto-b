<?php

namespace App\Http\Controllers;

use App\Models\CustomRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:achat,location,vente,expertise',
            'category' => 'nullable|string',
            'budget' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string',
        ]);

        CustomRequest::create($validated);

        return back()->with('success', 'Votre demande a été envoyée avec succès !');
    }

    public function index()
    {
        return Inertia::render('Admin/CustomRequests/Index', [
            'requests' => CustomRequest::latest()->paginate(15)
        ]);
    }

    public function updateStatus(Request $request, CustomRequest $customRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $customRequest->update($validated);

        return back();
    }
}
