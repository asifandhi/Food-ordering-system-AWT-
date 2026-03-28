<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelierProfile;
use App\Services\LocationService;
use Illuminate\Http\Request;

class NearbyHotelController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = (float) $request->lat;
        $lng = (float) $request->lng;

        // Raw SQL Haversine query for performance
        $restaurants = HotelierProfile::selectRaw("
                *,
                ( 6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) AS distance_km
            ", [$lat, $lng, $lat])
            ->where('status', 'approved')
            ->where('is_open', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->havingRaw('distance_km <= delivery_radius_km')
            ->orderBy('distance_km')
            ->with('deliverySlabs')
            ->get();

        $result = $restaurants->map(function ($r) use ($lat, $lng) {
            $deliveryInfo = LocationService::getDeliveryInfo(
                $r->deliverySlabs, $r->distance_km
            );
            return [
                'id'             => $r->id,
                'name'           => $r->hotel_name,
                'cuisine'        => $r->cuisine_type,
                'distance_km'    => round($r->distance_km, 2),
                'delivery_charge'=> $deliveryInfo['charge'],
                'estimated_time' => $deliveryInfo['estimated_time'],
                'rating'         => $r->rating,
                'logo'           => $r->hotel_logo ? asset($r->hotel_logo) : null,
            ];
        });

        return response()->json([
            'success'     => true,
            'count'       => $result->count(),
            'restaurants' => $result,
        ]);
    }
}