<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Events\NewOrderReceived;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Review;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('foodItem.hotelier.deliverySlabs')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.browse')
                ->with('error', 'Your cart is empty.');
        }

        $hotelier = $cartItems->first()->foodItem->hotelier;
        $subtotal = $cartItems->sum(fn($c) => $c->quantity * $c->foodItem->price);
        $profile = $user->customerProfile;
        $addresses = $user->savedAddresses()->get();

        $customerLat = $profile->latitude ?? session('customer_lat');
        $customerLng = $profile->longitude ?? session('customer_lng');

        $distance = null;
        $deliveryInfo = ['charge' => 0, 'estimated_time' => 30, 'deliverable' => true];

        if ($customerLat && $customerLng && $hotelier->latitude && $hotelier->longitude) {
            $distance = LocationService::getDistanceKm(
                $customerLat,
                $customerLng,
                $hotelier->latitude,
                $hotelier->longitude
            );
            $deliveryInfo = LocationService::getDeliveryInfo($hotelier->deliverySlabs, $distance);
        }

        // Free delivery check
        if ($hotelier->free_delivery_above && $subtotal >= $hotelier->free_delivery_above) {
            $deliveryInfo['charge'] = 0;
        }

        $grandTotal = $subtotal + $deliveryInfo['charge'];

        return view('customer.checkout', compact(
            'cartItems',
            'hotelier',
            'subtotal',
            'deliveryInfo',
            'distance',
            'grandTotal',
            'addresses',
            'customerLat',
            'customerLng'
        ));
    }

    public function place(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string',
            'delivery_lat' => 'nullable|numeric',
            'delivery_lng' => 'nullable|numeric',
            'payment_method' => 'required|in:cod,online',
        ]);

        $user = Auth::user();
        $cartItems = $user->cartItems()->with('foodItem.hotelier.deliverySlabs')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.browse')
                ->with('error', 'Your cart is empty.');
        }

        $hotelier = $cartItems->first()->foodItem->hotelier;
        $subtotal = $cartItems->sum(fn($c) => $c->quantity * $c->foodItem->price);

        // Check minimum order
        if ($hotelier->minimum_order && $subtotal < $hotelier->minimum_order) {
            return back()->with(
                'error',
                'Minimum order amount is ₹' . $hotelier->minimum_order
            );
        }

        $deliveryLat = $request->delivery_lat;
        $deliveryLng = $request->delivery_lng;
        $distance = null;
        $deliveryCharge = 0;
        $estimatedTime = $hotelier->avg_delivery_time;

        if ($deliveryLat && $deliveryLng && $hotelier->latitude && $hotelier->longitude) {
            $distance = LocationService::getDistanceKm(
                $deliveryLat,
                $deliveryLng,
                $hotelier->latitude,
                $hotelier->longitude
            );
            $deliveryInfo = LocationService::getDeliveryInfo($hotelier->deliverySlabs, $distance);
            $deliveryCharge = $deliveryInfo['charge'];
            $estimatedTime = $deliveryInfo['estimated_time'];
        }

        // Free delivery check
        if ($hotelier->free_delivery_above && $subtotal >= $hotelier->free_delivery_above) {
            $deliveryCharge = 0;
        }

        $grandTotal = $subtotal + $deliveryCharge;

        DB::transaction(function () use ($user, $cartItems, $hotelier, $subtotal, $deliveryCharge, $grandTotal, $distance, $estimatedTime, $request, $deliveryLat, $deliveryLng) {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'hotelier_id' => $hotelier->id,
                'total_amount' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'grand_total' => $grandTotal,
                'delivery_lat' => $deliveryLat,
                'delivery_lng' => $deliveryLng,
                'distance_km' => $distance,
                'estimated_delivery_time' => $estimatedTime,
                'delivery_address' => $request->delivery_address,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $subtotalItem = $cartItem->quantity * $cartItem->foodItem->price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem->item_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->foodItem->price,
                    'subtotal' => $subtotalItem,
                ]);
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'amount' => $grandTotal,
                'method' => $request->payment_method,
                'status' => 'pending',
            ]);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            session(['last_order_id' => $order->id]);
            // Fire WebSocket event — notify hotelier of new order
            broadcast(new NewOrderReceived($order))->toOthers();
        });

        return redirect()->route('customer.orders.show', session('last_order_id'))
            ->with('success', 'Order placed successfully!');
    }

    public function index()
{
    $orders = DB::table('orders')
        ->join('hotelier_profiles', 'orders.hotelier_id', '=', 'hotelier_profiles.id')
        ->select(
            'orders.id',
            'orders.grand_total',
            'orders.status',
            'orders.payment_method',
            'orders.created_at',
            'orders.hotelier_id',
            'hotelier_profiles.hotel_name'
        )
        ->where('orders.user_id', Auth::id())
        ->orderByDesc('orders.created_at')
        ->get();

    // Mark which orders already have a review
    $reviewedHotelierIds = DB::table('reviews')
        ->where('user_id', Auth::id())
        ->pluck('hotelier_id')
        ->toArray();

    $orders = $orders->map(function ($order) use ($reviewedHotelierIds) {
        $order->has_review = in_array($order->hotelier_id, $reviewedHotelierIds);
        return $order;
    });

    return view('customer.orders.index', compact('orders'));
}

    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('hotelier', 'orderItems.foodItem', 'payment')
            ->firstOrFail();

        $canReview = $order->status === 'delivered'
            && !Review::where('user_id', Auth::id())
                ->where('hotelier_id', $order->hotelier_id)
                ->where('created_at', '>=', $order->created_at)
                ->exists();

        return view('customer.order-detail', compact('order', 'canReview'));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'hotelier_id' => 'required|exists:hotelier_profiles,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'hotelier_id' => $request->hotelier_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Update hotelier average rating
        $avg = Review::where('hotelier_id', $request->hotelier_id)->avg('rating');
        \App\Models\HotelierProfile::where('id', $request->hotelier_id)
            ->update(['rating' => round($avg, 2)]);

        return back()->with('success', 'Review submitted! Thank you.');
    }
}