<?php

namespace App\Http\Controllers\Hotelier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $profile = $user->hotelierProfile;

        if (!$profile) {
            return redirect()->route('hotelier.profile')
                ->with('error', 'Please complete your profile first.');
        }

        $hotelierId = $profile->id;

        // Order counts
        $totalOrders     = Order::where('hotelier_id', $hotelierId)->count();
        $pendingOrders   = Order::where('hotelier_id', $hotelierId)->where('status', 'pending')->count();
        $todayOrders     = Order::where('hotelier_id', $hotelierId)
                            ->whereDate('created_at', today())->count();
        $deliveredOrders = Order::where('hotelier_id', $hotelierId)->where('status', 'delivered')->count();

        // Revenue
        $totalRevenue = Order::where('hotelier_id', $hotelierId)
                            ->where('status', 'delivered')
                            ->sum('grand_total');

        $todayRevenue = Order::where('hotelier_id', $hotelierId)
                            ->where('status', 'delivered')
                            ->whereDate('created_at', today())
                            ->sum('grand_total');

        // Recent 5 orders
        $recentOrders = Order::where('hotelier_id', $hotelierId)
                            ->with('customer')
                            ->latest()
                            ->take(5)
                            ->get();

        return view('hotelier.dashboard', compact(
            'profile', 'totalOrders', 'pendingOrders',
            'todayOrders', 'deliveredOrders',
            'totalRevenue', 'todayRevenue', 'recentOrders'
        ));
    }
}