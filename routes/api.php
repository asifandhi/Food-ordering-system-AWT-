<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\NearbyHotelController;
use App\Http\Controllers\Api\DeliveryPriceController;

// Nearby restaurants based on customer GPS
Route::get('/nearby',           [LocationController::class,     'nearby']);

// Nearby hotels using raw Haversine SQL
Route::get('/nearby-hotels',    [NearbyHotelController::class,  'index']);

// Calculate delivery price for specific restaurant
Route::get('/delivery-price',   [DeliveryPriceController::class,'calculate']);

// Order status polling (customer tracking)
Route::get('/order-status', function (\Illuminate\Http\Request $request) {
    $order = \App\Models\Order::findOrFail($request->order_id);
    return response()->json([
        'status' => $order->status,
        'label'  => ucfirst(str_replace('_', ' ', $order->status)),
    ]);
});

// New order check (hotelier dashboard polling)
Route::get('/check-new-orders', function (\Illuminate\Http\Request $request) {
    $hotelier    = \App\Models\HotelierProfile::findOrFail($request->hotelier_id);
    $latestOrder = \App\Models\Order::where('hotelier_id', $hotelier->id)
                        ->latest()->first();
    $pendingCount = \App\Models\Order::where('hotelier_id', $hotelier->id)
                        ->where('status', 'pending')->count();

    $hasNew = $latestOrder && $latestOrder->id > (int)$request->last_order_id;

    return response()->json([
        'has_new_order'   => $hasNew,
        'latest_order_id' => $latestOrder?->id ?? 0,
        'pending_count'   => $pendingCount,
    ]);
});