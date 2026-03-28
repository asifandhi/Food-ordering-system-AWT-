<?php

namespace App\Http\Controllers\Hotelier;

use App\Http\Controllers\Controller;
use App\Models\HotelierProfile;
use App\Models\DeliveryPricingSlab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $profile = $user->hotelierProfile;
        $slabs   = $profile ? $profile->deliverySlabs()->orderBy('min_km')->get() : collect();

        return view('hotelier.profile', compact('profile', 'slabs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hotel_name'        => 'required|string|max:150',
            'cuisine_type'      => 'required|string|max:100',
            'address'           => 'required|string',
            'city'              => 'required|string|max:100',
            'pincode'           => 'required|digits:6',
            'opening_time'      => 'required',
            'closing_time'      => 'required',
            'delivery_radius_km'=> 'required|numeric|min:1',
            'minimum_order'     => 'required|numeric|min:0',
            'avg_delivery_time' => 'required|integer|min:1',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
        ]);

        $user    = Auth::user();
        $profile = $user->hotelierProfile;

        // Handle logo upload
        $logoPath = $profile->hotel_logo ?? null;
        if ($request->hasFile('hotel_logo')) {
            $logo     = $request->file('hotel_logo');
            $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/hotels'), $logoName);
            $logoPath = 'uploads/hotels/' . $logoName;
        }

        // Handle banner upload
        $bannerPath = $profile->hotel_banner ?? null;
        if ($request->hasFile('hotel_banner')) {
            $banner     = $request->file('hotel_banner');
            $bannerName = time() . '_banner.' . $banner->getClientOriginalExtension();
            $banner->move(public_path('uploads/hotels'), $bannerName);
            $bannerPath = 'uploads/hotels/' . $bannerName;
        }

        $profile->update([
            'hotel_name'         => $request->hotel_name,
            'cuisine_type'       => $request->cuisine_type,
            'description'        => $request->description,
            'address'            => $request->address,
            'city'               => $request->city,
            'pincode'            => $request->pincode,
            'latitude'           => $request->latitude,
            'longitude'          => $request->longitude,
            'opening_time'       => $request->opening_time,
            'closing_time'       => $request->closing_time,
            'delivery_radius_km' => $request->delivery_radius_km,
            'minimum_order'      => $request->minimum_order,
            'avg_delivery_time'  => $request->avg_delivery_time,
            'free_delivery_above'=> $request->free_delivery_above,
            'hotel_logo'         => $logoPath,
            'hotel_banner'       => $bannerPath,
        ]);

        return redirect()->route('hotelier.profile')
            ->with('success', 'Profile updated successfully!');
    }

    public function toggleOpen(Request $request)
    {
        $profile = Auth::user()->hotelierProfile;
        $profile->update(['is_open' => !$profile->is_open]);

        return back()->with('success', $profile->is_open
            ? 'You are now OPEN for orders!'
            : 'You are now CLOSED for orders.');
    }

    public function storeSlabs(Request $request)
    {
        $request->validate([
            'slabs'                    => 'required|array|min:1',
            'slabs.*.min_km'           => 'required|numeric|min:0',
            'slabs.*.max_km'           => 'required|numeric|min:0',
            'slabs.*.delivery_charge'  => 'required|numeric|min:0',
            'slabs.*.estimated_time_min' => 'required|integer|min:1',
        ]);

        $hotelierId = Auth::user()->hotelierProfile->id;

        // Delete old slabs and recreate
        DeliveryPricingSlab::where('hotelier_id', $hotelierId)->delete();

        foreach ($request->slabs as $slab) {
            DeliveryPricingSlab::create([
                'hotelier_id'       => $hotelierId,
                'min_km'            => $slab['min_km'],
                'max_km'            => $slab['max_km'],
                'delivery_charge'   => $slab['delivery_charge'],
                'estimated_time_min'=> $slab['estimated_time_min'],
            ]);
        }

        return back()->with('success', 'Delivery pricing slabs saved!');
    }
}