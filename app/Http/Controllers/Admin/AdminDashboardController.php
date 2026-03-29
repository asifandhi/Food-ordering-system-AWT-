<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers'   => DB::table('users')->where('role', 'customer')->count(),
            'total_hoteliers'   => DB::table('hotelier_profiles')->count(),
            'pending_hoteliers' => DB::table('hotelier_profiles')->where('status', 'pending')->count(),
            'total_orders'      => DB::table('orders')->count(),
            'total_revenue'     => DB::table('orders')->where('status', 'delivered')->sum('grand_total'),
            'today_orders'      => DB::table('orders')->whereDate('created_at', today())->count(),
            'today_revenue'     => DB::table('orders')->whereDate('created_at', today())->where('status', 'delivered')->sum('grand_total'),
        ];

        $revenueChart = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as revenue'))
            ->where('status', 'delivered')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $recentOrders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('hotelier_profiles', 'orders.hotelier_id', '=', 'hotelier_profiles.id')
            ->select(
                'orders.id',
                'orders.grand_total',
                'orders.status',
                'orders.created_at',
                'users.name as customer_name',
                'hotelier_profiles.hotel_name'
            )
            ->orderByDesc('orders.created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'revenueChart', 'recentOrders'));
    }
}