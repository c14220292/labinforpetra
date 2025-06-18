<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = Pengguna::where('google_id', $googleUser->id)
                           ->orWhere('email', $googleUser->email)
                           ->first();

            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
                
                // Update last login
                $user->update(['last_login' => now()]);
                
                Auth::login($user);
                
                // Redirect based on role
                return $this->redirectBasedOnRole($user);
            } else {
                // Create new user
                $user = Pengguna::create([
                    'nama_pengguna' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => null, // Will be assigned by admin
                ]);

                Auth::login($user);
                
                return redirect()->route('dashboard')->with('info', 'Akun berhasil dibuat. Harap menunggu admin untuk memberikan role dan akses laboratorium.');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }

    private function redirectBasedOnRole($user)
    {
        if (!$user->role) {
            return redirect()->route('dashboard')->with('info', 'Akun anda belum divalidasi, harap menunggu manajemen role dari admin.');
        }

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'kepalalab':
                return redirect()->route('kepalalab.dashboard');
            case 'asistenlab':
                return redirect()->route('asistenlab.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}