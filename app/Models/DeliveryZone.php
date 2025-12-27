<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = ['name', 'postal_codes', 'cost', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'cost' => 'decimal:2'
    ];

    public function getPostalCodesArray()
    {
        return array_map('trim', explode(',', $this->postal_codes));
    }

    public static function findCostByPostalCode($postalCode)
    {
        $zones = self::where('is_active', true)->get();
        
        foreach ($zones as $zone) {
            $codes = $zone->getPostalCodesArray();
            
            foreach ($codes as $code) {
                // Support pour les wildcards (ex: 97*, 971*)
                if (str_contains($code, '*')) {
                    // Remplacer * par .* pour matcher n'importe quel caractère
                    $pattern = '/^' . str_replace('*', '.*', preg_quote($code, '/')) . '/';
                    if (preg_match($pattern, $postalCode)) {
                        return $zone->cost;
                    }
                } elseif ($postalCode === $code || str_starts_with($postalCode, $code)) {
                    return $zone->cost;
                }
            }
        }
        
        return null;
    }
}
