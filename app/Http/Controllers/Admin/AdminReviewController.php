<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->join('hotelier_profiles', 'reviews.hotelier_id', '=', 'hotelier_profiles.id')
            ->select(
                'reviews.id',
                'reviews.rating',
                'reviews.comment',
                'reviews.created_at',
                'users.name as customer_name',
                'hotelier_profiles.hotel_name'
            );

        if ($request->filled('rating')) {
            $query->where('reviews.rating', $request->rating);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('users.name', 'like', '%' . $request->search . '%')
                  ->orWhere('hotelier_profiles.hotel_name', 'like', '%' . $request->search . '%');
            });
        }

        $reviews = $query->orderByDesc('reviews.created_at')->paginate(15);

        // Stats
        $stats = [
            'total'   => DB::table('reviews')->count(),
            'avg'     => round(DB::table('reviews')->avg('rating'), 1),
            'five'    => DB::table('reviews')->where('rating', 5)->count(),
            'one_two' => DB::table('reviews')->whereIn('rating', [1, 2])->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function destroy($id)
    {
        DB::table('reviews')->where('id', $id)->delete();
        return back()->with('success', 'Review deleted successfully!');
    }
}