<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\HotelierProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    // ── Show customer register form ────────────────────────
    public function showCustomerRegister()
    {
        return view('auth.register-customer');
    }

    // ── Show hotelier register form ────────────────────────
    public function showHotelierRegister()
    {
        return view('auth.register-hotelier');
    }

    // ── Handle customer registration ───────────────────────
    public function customerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'city' => 'required|string|max:100',
            'pincode' => 'required|regex:/^[0-9]{6}$/',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'customer',
            'status' => 'active',
        ]);

        // Create customer profile
        CustomerProfile::create([
            'user_id' => $user->id,
            'city' => $request->city,
            'pincode' => $request->pincode,
        ]);
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            // Mail failure does not block registration
        }
        // Auto login after register
        Auth::login($user);

        return redirect()->route('customer.browse')
            ->with('success', 'Welcome! Your account has been created.');
    }

    // ── Handle hotelier registration ───────────────────────
    public function hotelierRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'pincode' => 'required|regex:/^[0-9]{6}$/',
            'hotel_name' => 'required|string|max:150',
            'cuisine_type' => 'required|string|max:100',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'gstin' => 'nullable|string|max:20',
        ]);

        // Create user — status pending until admin approves
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'hotelier',
            'status' => 'active',
        ]);

        // Create hotelier profile — status = pending
        HotelierProfile::create([
            'user_id' => $user->id,
            'hotel_name' => $request->hotel_name,
            'cuisine_type' => $request->cuisine_type,
            'address' => $request->address,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'gstin' => $request->gstin,
            'status' => 'pending',
        ]);

        // Do NOT auto-login — must wait for admin approval
        return redirect()->route('login.hotelier')
            ->with('success', 'Registration successful! Please wait for admin approval before logging in.');
    }
}