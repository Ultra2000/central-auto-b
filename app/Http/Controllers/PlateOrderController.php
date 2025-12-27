<?php

namespace App\Http\Controllers;

use App\Models\PlateOrder;
use App\Mail\PlateOrderNotification;
use App\Mail\PlateOrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PlateOrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_type' => 'required|in:definitive,provisional',
            'plate_number' => 'required|string|max:20',
            'department' => 'required|string|max:3',
            'custom_text' => 'nullable|string|max:40',
            'material' => 'required|in:plexiglass,alu',
            'quantity' => 'required|integer|min:1|max:100',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'delivery_option' => 'required|in:pickup,delivery',
            'delivery_address' => 'nullable|string|max:500',
            'delivery_city' => 'nullable|string|max:100',
            'delivery_postal_code' => 'nullable|string|max:10',
            'delivery_cost' => 'nullable|numeric|min:0',
        ]);

        $order = PlateOrder::create($validated);

        // Envoyer l'email de confirmation au client
        Mail::to($order->customer_email)->send(new PlateOrderConfirmation($order));

        // Envoyer la notification à l'admin
        Mail::to(config('mail.admin_email', 'admin@centralauto-b.com'))->send(new PlateOrderNotification($order));

        return redirect()->back()->with('success', 'Votre commande de plaque a été enregistrée avec succès !');
    }

    public function index()
    {
        $orders = PlateOrder::latest()->paginate(20);
        
        return Inertia::render('Admin/PlateOrders/Index', [
            'orders' => $orders
        ]);
    }

    public function updateStatus(PlateOrder $order, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès !');
    }
}
