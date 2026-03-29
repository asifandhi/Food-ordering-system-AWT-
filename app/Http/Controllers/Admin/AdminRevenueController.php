<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRevenueController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');

        if ($period === 'daily') {
            $revenueData = DB::table('orders')
                ->select(DB::raw('DATE(created_at) as label'), DB::raw('SUM(grand_total) as revenue'), DB::raw('COUNT(*) as order_count'))
                ->where('status', 'delivered')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('label')->get();

        } elseif ($period === 'weekly') {
            $revenueData = DB::table('orders')
                ->select(DB::raw('YEARWEEK(created_at) as label'), DB::raw('SUM(grand_total) as revenue'), DB::raw('COUNT(*) as order_count'))
                ->where('status', 'delivered')
                ->where('created_at', '>=', now()->subWeeks(12))
                ->groupBy(DB::raw('YEARWEEK(created_at)'))
                ->orderBy('label')->get();

        } else {
            $revenueData = DB::table('orders')
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as label"), DB::raw('SUM(grand_total) as revenue'), DB::raw('COUNT(*) as order_count'))
                ->where('status', 'delivered')
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                ->orderBy('label')->get();
        }

        $topHoteliers = DB::table('orders')
            ->join('hotelier_profiles', 'orders.hotelier_id', '=', 'hotelier_profiles.id')
            ->select('hotelier_profiles.hotel_name', DB::raw('SUM(orders.grand_total) as revenue'), DB::raw('COUNT(*) as order_count'))
            ->where('orders.status', 'delivered')
            ->groupBy('hotelier_profiles.id', 'hotelier_profiles.hotel_name')
            ->orderByDesc('revenue')
            ->limit(10)->get();

        $statusBreakdown = DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')->get();

        $totals = [
            'total_revenue'   => DB::table('orders')->where('status', 'delivered')->sum('grand_total'),
            'total_orders'    => DB::table('orders')->count(),
            'avg_order_value' => DB::table('orders')->where('status', 'delivered')->avg('grand_total') ?? 0,
        ];

        return view('admin.revenue.index', compact('revenueData', 'topHoteliers', 'statusBreakdown', 'totals', 'period'));
    }
}