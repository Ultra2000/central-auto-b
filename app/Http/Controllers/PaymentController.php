<?php

namespace App\Http\Controllers;

use App\Models\PlateOrder;
use App\Mail\PlateOrderNotification;
use App\Mail\PlateOrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    /**
     * Créer une session de paiement Stripe pour une commande de plaque
     */
    public function createCheckoutSession(Request $request)
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

        // Créer la commande
        $order = PlateOrder::create(array_merge($validated, [
            'payment_status' => 'pending',
        ]));

        // Créer la session Stripe Checkout
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Plaque d\'immatriculation ' . $validated['plate_type'],
                            'description' => "Numéro: {$validated['plate_number']} - Matériau: {$validated['material']}",
                            'images' => [asset('images/unnamed.png')],
                        ],
                        'unit_amount' => (int)($validated['unit_price'] * 100), // Convertir en centimes
                    ],
                    'quantity' => $validated['quantity'],
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel') . '?order_id=' . $order->id,
            'customer_email' => $validated['customer_email'],
            'metadata' => [
                'order_id' => $order->id,
                'order_type' => 'plate',
            ],
        ]);

        // Sauvegarder l'ID de session
        $order->update([
            'stripe_session_id' => $session->id,
            'payment_method' => 'stripe',
        ]);

        return response()->json([
            'sessionId' => $session->id,
            'orderId' => $order->id,
        ]);
    }

    /**
     * Page de succès après paiement
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('welcome')->with('error', 'Session de paiement invalide');
        }

        // Récupérer la session Stripe
        $session = Session::retrieve($sessionId);

        // Trouver la commande
        $order = PlateOrder::where('stripe_session_id', $sessionId)->first();

        if (!$order) {
            return redirect()->route('welcome')->with('error', 'Commande introuvable');
        }

        // Mettre à jour le statut de paiement
        if ($session->payment_status === 'paid' && $order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'stripe_payment_intent_id' => $session->payment_intent,
                'paid_at' => now(),
            ]);

            // Envoyer les emails de confirmation
            Mail::to($order->customer_email)->send(new PlateOrderConfirmation($order));
            Mail::to(config('mail.admin_email', 'admin@centralauto-b.com'))->send(new PlateOrderNotification($order));
        }

        return inertia('Payment/Success', [
            'order' => $order,
        ]);
    }

    /**
     * Page d'annulation
     */
    public function cancel(Request $request)
    {
        $orderId = $request->get('order_id');

        if ($orderId) {
            $order = PlateOrder::find($orderId);
            if ($order && $order->payment_status === 'pending') {
                $order->update(['payment_status' => 'failed']);
            }
        }

        return inertia('Payment/Cancel', [
            'orderId' => $orderId,
        ]);
    }

    /**
     * Webhook Stripe pour les événements de paiement
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('cashier.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook error'], 400);
        }

        // Gérer les événements
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            
            $order = PlateOrder::where('stripe_session_id', $session->id)->first();
            
            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'paid_at' => now(),
                ]);

                // Envoyer les emails
                Mail::to($order->customer_email)->send(new PlateOrderConfirmation($order));
                Mail::to(config('mail.admin_email'))->send(new PlateOrderNotification($order));
            }
        }

        return response()->json(['status' => 'success']);
    }
}
