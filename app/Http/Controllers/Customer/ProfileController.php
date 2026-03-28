<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerSavedAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $profile   = $user->customerProfile;
        $addresses = $user->savedAddresses()->get();
        $orders    = $user->orders()->latest()->take(5)->get();

        return view('customer.profile', compact('profile', 'addresses', 'orders'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'city'  => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        if ($user->customerProfile) {
            $user->customerProfile->update(['city' => $request->city]);
        }

        return back()->with('success', 'Profile updated!');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label'        => 'required|in:home,work,other',
            'address_line' => 'required|string',
            'city'         => 'required|string|max:100',
            'pincode'      => 'required|regex:/^[0-9]{6}$/',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
        ]);

        $userId = Auth::id();

        // If first address — set as default
        $isDefault = CustomerSavedAddress::where('user_id', $userId)->count() === 0 ? 1 : 0;

        CustomerSavedAddress::create([
            'user_id'      => $userId,
            'label'        => $request->label,
            'address_line' => $request->address_line,
            'city'         => $request->city,
            'pincode'      => $request->pincode,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'is_default'   => $isDefault,
        ]);

        return back()->with('success', 'Address saved!');
    }

    public function deleteAddress($id)
    {
        CustomerSavedAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Address deleted.');
    }

    public function setDefault($id)
    {
        $userId = Auth::id();

        // Remove default from all
        CustomerSavedAddress::where('user_id', $userId)
            ->update(['is_default' => 0]);

        // Set new default
        CustomerSavedAddress::where('id', $id)
            ->where('user_id', $userId)
            ->update(['is_default' => 1]);

        return back()->with('success', 'Default address updated.');
    }
}