<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Mise à jour des zones de livraison...\n\n";

$zones = [
    [
        'name' => 'Pointe-à-Pitre Centre',
        'postal_codes' => '97110',
        'cost' => 0.00
    ],
    [
        'name' => 'Guadeloupe Nord',
        'postal_codes' => '971*',
        'cost' => 25.00
    ],
    [
        'name' => 'Guadeloupe Sud',
        'postal_codes' => '972*',
        'cost' => 30.00
    ],
    [
        'name' => 'Grande-Terre',
        'postal_codes' => '97100, 97118, 97119, 97120, 97122, 97125, 97127, 97129, 97130, 97131, 97134, 97139, 97140, 97141, 97142, 97143, 97190',
        'cost' => 35.00
    ],
];

foreach ($zones as $zoneData) {
    $zone = \App\Models\DeliveryZone::where('name', $zoneData['name'])->first();
    if ($zone) {
        $zone->postal_codes = $zoneData['postal_codes'];
        $zone->cost = $zoneData['cost'];
        $zone->save();
        echo "✓ {$zone->name} mis à jour: {$zone->postal_codes}\n";
    }
}

echo "\n--- Test des codes postaux ---\n";
$testCodes = ['97110', '97100', '97190', '97150'];
foreach ($testCodes as $code) {
    $cost = \App\Models\DeliveryZone::findCostByPostalCode($code);
    echo "Code $code: " . ($cost ? "{$cost}€" : "Non disponible") . "\n";
}
