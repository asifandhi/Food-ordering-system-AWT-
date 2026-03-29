<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('hotelier_profiles', 'orders.hotelier_id', '=', 'hotelier_profiles.id')
            ->select(
                'orders.id',
                'orders.grand_total',
                'orders.status',
                'orders.payment_method',
                'orders.payment_status',
                'orders.created_at',
                'users.name as customer_name',
                'users.email as customer_email',
                'hotelier_profiles.hotel_name'
            );

        if ($request->filled('status')) {
            $query->where('orders.status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('users.name', 'like', '%' . $request->search . '%')
                  ->orWhere('hotelier_profiles.hotel_name', 'like', '%' . $request->search . '%')
                  ->orWhere('orders.id', $request->search);
            });
        }

        $orders = $query->orderByDesc('orders.created_at')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('hotelier_profiles', 'orders.hotelier_id', '=', 'hotelier_profiles.id')
            ->select(
                'orders.id',
                'orders.grand_total',
                'orders.total_amount',
                'orders.delivery_charge',
                'orders.delivery_address',
                'orders.status',
                'orders.payment_method',
                'orders.payment_status',
                'orders.estimated_delivery_time',
                'orders.created_at',
                'users.name as customer_name',
                'users.email as customer_email',
                'users.phone as customer_phone',
                'hotelier_profiles.hotel_name',
                'hotelier_profiles.city as hotelier_city'
            )
            ->where('orders.id', $id)
            ->first();

        if (!$order) abort(404);

        $orderItems = DB::table('order_items')
            ->join('food_items', 'order_items.item_id', '=', 'food_items.id')
            ->select(
                'order_items.quantity',
                'order_items.unit_price',
                'order_items.subtotal',
                'food_items.name as item_name',
                'food_items.image as item_image'
            )
            ->where('order_items.order_id', $id)
            ->get();

        return view('admin.orders.show', compact('order', 'orderItems'));
    }
}