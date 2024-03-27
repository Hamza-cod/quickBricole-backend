<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();
            $guards =  ['handyman','web',];
            foreach($guards as $guard)
            {
                $currenGuard = Auth::guard($guard);
                if($currenGuard->check())
                {
                    $user = $currenGuard->user();
                    
                }
            }
        $request->session()->regenerate();
        $token = $user->createToken('api',[$user->getRoleAttribute()])->plainTextToken;
        return response(compact('user','token'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
         $guards =  ['handyman','web',];
            foreach($guards as $guard)
            {
                $currenGuard = Auth::guard($guard);
                if($currenGuard->check())
                {
                    $user = $currenGuard->user();
                    break;
                }
            }
            $user->tokens()->delete();
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
