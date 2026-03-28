<?php

namespace App\Http\Controllers\Hotelier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\OrderStatusUpdated;

class OrderManageController extends Controller
{
    public function index()
    {
        $hotelierId = Auth::user()->hotelierProfile->id;

        $orders = Order::where('hotelier_id', $hotelierId)
            ->with('customer', 'orderItems.foodItem')
            ->latest()
            ->paginate(15);

        return view('hotelier.orders', compact('orders'));
    }

    public function show($id)
    {
        $hotelierId = Auth::user()->hotelierProfile->id;

        $order = Order::where('id', $id)
            ->where('hotelier_id', $hotelierId)
            ->with('customer', 'orderItems.foodItem', 'payment')
            ->firstOrFail();

        return view('hotelier.order-detail', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,preparing,out_for_delivery,delivered,cancelled',
        ]);

        $hotelierId = Auth::user()->hotelierProfile->id;

        $order = Order::where('id', $id)
            ->where('hotelier_id', $hotelierId)
            ->firstOrFail();

        $order->update(['status' => $request->status]);
        // Fire WebSocket event — notify customer of status change
        broadcast(new OrderStatusUpdated($order))->toOthers();

        // If delivered and COD — mark payment as paid
        if ($request->status === 'delivered' && $order->payment_method === 'cod') {
            $order->update(['payment_status' => 'paid']);
        }

        return back()->with('success', 'Order status updated to: ' . strtoupper($request->status));
    }
}