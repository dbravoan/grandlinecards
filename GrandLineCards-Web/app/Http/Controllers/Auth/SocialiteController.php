<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Update existing user with provider info if missing
                if (!$user->provider_id) {
                    $user->update([
                        'provider_id' => $socialUser->getId(),
                        'provider_name' => $provider,
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Capitán Desconocido',
                    'email' => $socialUser->getEmail(),
                    'provider_id' => $socialUser->getId(),
                    'provider_name' => $provider,
                    'avatar' => $socialUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(), // Auto-verify social logins
                ]);
            }

            Auth::login($user, true);

            return redirect()->route('profile.dashboard'); 
        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', 'Error de autenticación: ' . $e->getMessage());
        }
    }
}
