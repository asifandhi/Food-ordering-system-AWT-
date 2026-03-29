<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HotelierProfile;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrowseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profile = $user->customerProfile;

        // Get customer location from profile or session
        $customerLat = $profile->latitude ?? session('customer_lat');
        $customerLng = $profile->longitude ?? session('customer_lng');

        // Save location from request if submitted manually
        if ($request->has('lat') && $request->has('lng')) {
            $customerLat = $request->lat;
            $customerLng = $request->lng;
            session(['customer_lat' => $customerLat, 'customer_lng' => $customerLng]);

            // Save to profile
            if ($profile) {
                $profile->update([
                    'latitude' => $customerLat,
                    'longitude' => $customerLng,
                    'city' => $request->city ?? $profile->city,
                ]);
            }
        }

        // Get all approved open restaurants
        $restaurants = HotelierProfile::where('status', 'approved')
            ->with('deliverySlabs')
            ->get();

        // Calculate distance and delivery info for each restaurant
        $restaurantsWithDistance = $restaurants->map(function ($restaurant) use ($customerLat, $customerLng) {
            $distance = null;
            $deliveryInfo = ['charge' => '—', 'estimated_time' => '—', 'deliverable' => false];

            if ($customerLat && $customerLng && $restaurant->latitude && $restaurant->longitude) {
                $distance = LocationService::getDistanceKm(
                    $customerLat,
                    $customerLng,
                    $restaurant->latitude,
                    $restaurant->longitude
                );
                $deliveryInfo = LocationService::getDeliveryInfo($restaurant->deliverySlabs, $distance);
            }

            $restaurant->distance = $distance;
            $restaurant->deliveryInfo = $deliveryInfo;
            return $restaurant;
        });

        // Filter by delivery radius and sort nearest first
        if ($customerLat && $customerLng) {
            $restaurantsWithDistance = $restaurantsWithDistance
                ->filter(fn($r) => $r->deliveryInfo['deliverable'] === true)
                ->sortBy('distance');
        }

        // Search filter
        if ($request->search) {
            $search = strtolower($request->search);
            $restaurantsWithDistance = $restaurantsWithDistance->filter(
                fn($r) => str_contains(strtolower($r->hotel_name), $search)
                || str_contains(strtolower($r->cuisine_type), $search)
            );
        }

        // Cuisine filter
        if ($request->cuisine) {
            $restaurantsWithDistance = $restaurantsWithDistance->filter(
                fn($r) => strtolower($r->cuisine_type) === strtolower($request->cuisine)
            );
        }

        $cuisines = HotelierProfile::where('status', 'approved')
            ->distinct()->pluck('cuisine_type');

        return view('customer.browse', compact(
            'restaurantsWithDistance',
            'customerLat',
            'customerLng',
            'cuisines'
        ));
    }

    public function restaurant($id)
    {
        $restaurant = HotelierProfile::where('id', $id)
            ->where('status', 'approved')
            ->with(['categories.foodItems', 'deliverySlabs'])
            ->firstOrFail();

        $user = Auth::user();
        $profile = $user->customerProfile;
        $customerLat = $profile->latitude ?? session('customer_lat');
        $customerLng = $profile->longitude ?? session('customer_lng');

        $distance = null;
        $deliveryInfo = ['charge' => 0, 'estimated_time' => 30, 'deliverable' => true];

        if ($customerLat && $customerLng && $restaurant->latitude && $restaurant->longitude) {
            $distance = LocationService::getDistanceKm(
                $customerLat,
                $customerLng,
                $restaurant->latitude,
                $restaurant->longitude
            );
            $deliveryInfo = LocationService::getDeliveryInfo($restaurant->deliverySlabs, $distance);
        }

        // Get customer cart for this restaurant
        $cartItems = $user->cartItems()
            ->whereHas('foodItem', fn($q) => $q->where('hotelier_id', $id))
            ->with('foodItem')
            ->get();

        $cartTotal = $cartItems->sum(fn($c) => $c->quantity * $c->foodItem->price);

        $reviews = \App\Models\Review::where('hotelier_id', $id)
            ->with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.restaurant', compact(
            'restaurant',
            'distance',
            'deliveryInfo',
            'cartItems',
            'cartTotal',
            'reviews'
        ));
    }
}