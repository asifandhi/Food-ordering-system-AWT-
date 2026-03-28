<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelierProfile;
use App\Services\LocationService;
use Illuminate\Http\Request;

class DeliveryPriceController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'hotelier_id'  => 'required|exists:hotelier_profiles,id',
            'customer_lat' => 'required|numeric',
            'customer_lng' => 'required|numeric',
        ]);

        $hotelier = HotelierProfile::with('deliverySlabs')
                        ->findOrFail($request->hotelier_id);

        $distance = LocationService::getDistanceKm(
            $request->customer_lat,
            $request->customer_lng,
            $hotelier->latitude,
            $hotelier->longitude
        );

        $deliveryInfo = LocationService::getDeliveryInfo(
            $hotelier->deliverySlabs, $distance
        );

        return response()->json([
            'success'        => true,
            'distance_km'    => $distance,
            'deliverable'    => $deliveryInfo['deliverable'],
            'delivery_charge'=> $deliveryInfo['charge'],
            'estimated_time' => $deliveryInfo['estimated_time'],
        ]);
    }
}