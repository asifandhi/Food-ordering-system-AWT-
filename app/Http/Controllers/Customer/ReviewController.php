<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function create($order_id)
    {
        $order = DB::table('orders')
            ->join('hotelier_profiles', 'orders.hotelier_id', '=', 'hotelier_profiles.id')
            ->select(
                'orders.id',
                'orders.hotelier_id',
                'orders.status',
                'orders.created_at',
                'hotelier_profiles.hotel_name'
            )
            ->where('orders.id', $order_id)
            ->where('orders.user_id', Auth::id())
            ->where('orders.status', 'delivered')
            ->first();

        if (!$order) {
            return redirect()->route('customer.orders')
                ->with('error', 'You can only review delivered orders.');
        }

        // Check already reviewed this hotelier for this order
        $alreadyReviewed = DB::table('reviews')
            ->where('user_id', Auth::id())
            ->where('hotelier_id', $order->hotelier_id)
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->route('customer.orders')
                ->with('error', 'You have already reviewed this restaurant.');
        }

        // Get ordered food items for optional per-item rating
        $orderItems = DB::table('order_items')
            ->join('food_items', 'order_items.item_id', '=', 'food_items.id')
            ->select('food_items.id', 'food_items.name', 'order_items.quantity')
            ->where('order_items.order_id', $order_id)
            ->get();

        return view('customer.reviews.create', compact('order', 'orderItems'));
    }

    public function store(Request $request, $order_id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'item_id' => 'nullable|exists:food_items,id',
        ]);

        $order = DB::table('orders')
            ->where('id', $order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->first();

        if (!$order) {
            return redirect()->route('customer.orders')
                ->with('error', 'Invalid order.');
        }

        DB::table('reviews')->insert([
            'user_id'     => Auth::id(),
            'hotelier_id' => $order->hotelier_id,
            'item_id'     => $request->item_id ?: null,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Recalculate and update avg rating on hotelier profile
        $avg = DB::table('reviews')
            ->where('hotelier_id', $order->hotelier_id)
            ->avg('rating');

        DB::table('hotelier_profiles')
            ->where('id', $order->hotelier_id)
            ->update(['rating' => round($avg, 2), 'updated_at' => now()]);

        return redirect()->route('customer.orders')
            ->with('success', '⭐ Thank you! Your review has been submitted.');
    }
}