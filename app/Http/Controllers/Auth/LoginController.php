<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ── Show customer login form ───────────────────────────
    public function showCustomerLogin()
    {
        return view('auth.login-customer');
    }

    // ── Show hotelier login form ───────────────────────────
    public function showHotelierLogin()
    {
        return view('auth.login-hotelier');
    }

    // ── Handle customer login ──────────────────────────────
    public function customerLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Make sure they are a customer
            if (!$user->isCustomer()) {
                Auth::logout();
                return back()->withErrors(['email' => 'This account is not a customer account.']);
            }

            // Check if blocked
            if ($user->status === 'blocked') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been blocked.']);
            }

            $request->session()->regenerate();
            return redirect()->route('customer.browse');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    // ── Handle hotelier login ──────────────────────────────
    public function hotelierLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Must be a hotelier
            if (!$user->isHotelier()) {
                Auth::logout();
                return back()->withErrors(['email' => 'This account is not a hotelier account.']);
            }

            // Check approval status
            $profile = $user->hotelierProfile;
            if (!$profile || $profile->status === 'pending') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is pending admin approval.']);
            }

            if ($profile->status === 'suspended') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended.']);
            }

            // Check if blocked
            if ($user->status === 'blocked') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been blocked.']);
            }

            $request->session()->regenerate();
            return redirect()->route('hotelier.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    // ── Logout ─────────────────────────────────────────────
    public function logout(Request $request)
    {
        $role = Auth::user()?->role;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'hotelier') {
            return redirect()->route('login.hotelier');
        }

        return redirect()->route('login.customer');
    }
}