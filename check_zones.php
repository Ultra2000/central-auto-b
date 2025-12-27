<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Zones de livraison disponibles:\n\n";

$zones = \App\Models\DeliveryZone::all();

if ($zones->isEmpty()) {
    echo "❌ Aucune zone de livraison trouvée!\n";
    echo "Exécutez la commande: php artisan db:seed --class=DeliveryZoneSeeder\n";
} else {
    foreach ($zones as $zone) {
        echo "✓ {$zone->name}\n";
        echo "  Code postal: {$zone->postal_code}\n";
        echo "  Prix: {$zone->cost} €\n\n";
    }
}

// Test avec un code postal
echo "\nTest avec le code postal 97100:\n";
$cost = \App\Models\DeliveryZone::findCostByPostalCode('97100');
if ($cost) {
    echo "✓ Coût trouvé: {$cost} €\n";
} else {
    echo "❌ Aucun coût trouvé pour ce code postal\n";
}
