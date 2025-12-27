<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Test d'envoi d'emails pour une commande de plaque...\n\n";

// Créer une commande de test
$order = \App\Models\PlateOrder::create([
    'plate_type' => 'definitive',
    'plate_number' => 'TEST-971',
    'department' => '971',
    'custom_text' => 'GUADELOUPE',
    'material' => 'plexiglass',
    'quantity' => 2,
    'unit_price' => 14.90,
    'total_price' => 54.80,
    'customer_name' => 'Jean Test',
    'customer_email' => 'jean.test@example.com',
    'customer_phone' => '0690123456',
    'customer_address' => '123 Rue Example',
    'status' => 'pending',
    'delivery_option' => 'delivery',
    'delivery_address' => '15 Rue de la République',
    'delivery_city' => 'Pointe-à-Pitre',
    'delivery_postal_code' => '97110',
    'delivery_cost' => 25.00,
]);

echo "✓ Commande de test créée (ID: {$order->id})\n\n";

// Envoyer l'email de confirmation au client
echo "Envoi de l'email de confirmation au client...\n";
\Illuminate\Support\Facades\Mail::to($order->customer_email)
    ->send(new \App\Mail\PlateOrderConfirmation($order));
echo "✓ Email de confirmation envoyé\n\n";

// Envoyer la notification à l'admin
echo "Envoi de la notification à l'admin...\n";
\Illuminate\Support\Facades\Mail::to(config('mail.admin_email'))
    ->send(new \App\Mail\PlateOrderNotification($order));
echo "✓ Notification admin envoyée\n\n";

echo "════════════════════════════════════════════════════════════════\n";
echo "Les emails ont été enregistrés dans: storage/logs/laravel.log\n";
echo "Recherchez 'MAIL_FROM_ADDRESS' pour voir les emails générés.\n";
echo "════════════════════════════════════════════════════════════════\n";

// Supprimer la commande de test
$order->delete();
echo "\n✓ Commande de test supprimée\n";
