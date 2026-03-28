<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelierApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->isHotelier()) {
            $profile = $user->hotelierProfile;

            if (!$profile || $profile->status === 'pending') {
                auth()->logout();
                return redirect()->route('login.hotelier')
                    ->withErrors(['email' => 'Your account is pending admin approval. Please wait.']);
            }

            if ($profile->status === 'suspended') {
                auth()->logout();
                return redirect()->route('login.hotelier')
                    ->withErrors(['email' => 'Your account has been suspended. Contact support.']);
            }
        }

        return $next($request);
    }
}