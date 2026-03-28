<?php

namespace App\Services;

class LocationService
{
    /**
     * Haversine formula — calculates distance between two GPS points in km
     */
    public static function getDistanceKm(
        float $lat1, float $lon1,
        float $lat2, float $lon2
    ): float {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Get delivery charge and ETA from slabs for a given distance
     */
    public static function getDeliveryInfo($slabs, float $distance): array
{
    // If no slabs defined — allow delivery with 0 charge
    if ($slabs->isEmpty()) {
        return [
            'charge'         => 0,
            'estimated_time' => 30,
            'deliverable'    => true,
        ];
    }

    foreach ($slabs as $slab) {
        if ($distance >= (float)$slab->min_km && $distance <= (float)$slab->max_km) {
            return [
                'charge'         => (float)$slab->delivery_charge,
                'estimated_time' => (int)$slab->estimated_time_min,
                'deliverable'    => true,
            ];
        }
    }

    // No slab matched — check if distance is within the last slab's max
    $lastSlab = $slabs->last();
    if ($lastSlab && $distance <= (float)$lastSlab->max_km) {
        return [
            'charge'         => (float)$lastSlab->delivery_charge,
            'estimated_time' => (int)$lastSlab->estimated_time_min,
            'deliverable'    => true,
        ];
    }

    return [
        'charge'         => 0,
        'estimated_time' => 0,
        'deliverable'    => false,
    ];
}
}