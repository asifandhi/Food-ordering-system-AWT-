<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelierProfile;
use App\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        $restaurants = HotelierProfile::where('status', 'approved')
            ->where('is_open', true)
            ->with('deliverySlabs')
            ->get();

        $result = $restaurants->map(function ($restaurant) use ($lat, $lng) {
            if (!$restaurant->latitude || !$restaurant->longitude) {
                return null;
            }

            $distance     = LocationService::getDistanceKm(
                $lat, $lng,
                $restaurant->latitude, $restaurant->longitude
            );
            $deliveryInfo = LocationService::getDeliveryInfo(
                $restaurant->deliverySlabs, $distance
            );

            if (!$deliveryInfo['deliverable']) return null;

            return [
                'id'             => $restaurant->id,
                'name'           => $restaurant->hotel_name,
                'cuisine'        => $restaurant->cuisine_type,
                'distance_km'    => $distance,
                'delivery_charge'=> $deliveryInfo['charge'],
                'estimated_time' => $deliveryInfo['estimated_time'],
                'rating'         => $restaurant->rating,
                'is_open'        => $restaurant->is_open,
                'logo'           => $restaurant->hotel_logo
                                    ? asset($restaurant->hotel_logo) : null,
            ];
        })->filter()->sortBy('distance_km')->values();

        return response()->json([
            'success'     => true,
            'count'       => $result->count(),
            'restaurants' => $result,
        ]);
    }
}