<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Configuration complète des zones de livraison...\n\n";

// Supprimer les anciennes zones
\App\Models\DeliveryZone::truncate();

// Créer des zones plus logiques
$zones = [
    [
        'name' => 'Pointe-à-Pitre Centre',
        'postal_codes' => '97110',
        'cost' => 0.00,
        'is_active' => true
    ],
    [
        'name' => 'Guadeloupe (971)',
        'postal_codes' => '971*',
        'cost' => 25.00,
        'is_active' => true
    ],
    [
        'name' => 'Martinique (972)',
        'postal_codes' => '972*',
        'cost' => 30.00,
        'is_active' => true
    ],
    [
        'name' => 'Guyane (973)',
        'postal_codes' => '973*',
        'cost' => 40.00,
        'is_active' => true
    ],
];

foreach ($zones as $zoneData) {
    $zone = \App\Models\DeliveryZone::create($zoneData);
    echo "✓ {$zone->name} créée: {$zone->postal_codes} = {$zone->cost}€\n";
}

echo "\n--- Test des codes postaux ---\n";
$testCodes = ['97110', '97100', '97150', '97200', '97300'];
foreach ($testCodes as $code) {
    $cost = \App\Models\DeliveryZone::findCostByPostalCode($code);
    echo "Code $code: " . ($cost !== null ? "{$cost}€" : "Non disponible") . "\n";
}
