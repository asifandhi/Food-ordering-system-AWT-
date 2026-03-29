<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminHotelierController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('hotelier_profiles')
            ->join('users', 'hotelier_profiles.user_id', '=', 'users.id')
            ->select(
                'hotelier_profiles.id',
                'hotelier_profiles.hotel_name',
                'hotelier_profiles.city',
                'hotelier_profiles.address',
                'hotelier_profiles.status',
                'hotelier_profiles.rating',
                'hotelier_profiles.created_at',
                'users.name as owner_name',
                'users.email as owner_email',
                'users.phone as owner_phone'
            );

        if ($request->filled('status')) {
            $query->where('hotelier_profiles.status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('hotelier_profiles.hotel_name', 'like', '%' . $request->search . '%')
                  ->orWhere('users.email', 'like', '%' . $request->search . '%')
                  ->orWhere('users.name', 'like', '%' . $request->search . '%');
            });
        }

        $hoteliers = $query->orderByDesc('hotelier_profiles.created_at')->paginate(10);

        return view('admin.hoteliers.index', compact('hoteliers'));
    }

    public function approve($id)
    {
        DB::table('hotelier_profiles')->where('id', $id)->update([
            'status'     => 'approved',
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Hotelier approved successfully!');
    }

    public function reject($id)
    {
        DB::table('hotelier_profiles')->where('id', $id)->update([
            'status'     => 'suspended',
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Hotelier suspended successfully!');
    }

    public function destroy($id)
    {
        DB::table('hotelier_profiles')->where('id', $id)->delete();
        return back()->with('success', 'Hotelier deleted successfully!');
    }
}