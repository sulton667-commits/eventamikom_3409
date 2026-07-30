<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class UserAuthController extends Controller
{
    // ====== CUSTOMER LOGIN ======
    public function showLoginUser()
    {
        return view('auth.login-user');
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (in_array($user->role, ['admin', 'partner'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'Gunakan halaman login yang sesuai dengan peran Anda.']);
            }

            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // ====== GOOGLE AUTH CUSTOMER ======
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('user.login')->withErrors(['email' => 'Gagal login via Google: ' . $e->getMessage()]);
        }

        // Find user by google_id or email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Check role restriction for customer login
            if (in_array($user->role, ['admin', 'partner'])) {
                return redirect()->route('user.login')->withErrors(['email' => 'Akun ini terdaftar sebagai ' . ucfirst($user->role) . '. Silakan login di portal yang sesuai.']);
            }

            // Update google_id if not set yet
            if (empty($user->google_id)) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            // Create new customer user
            $user = User::create([
                'name'      => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password'  => Hash::make(Str::random(16)),
                'role'      => 'user',
            ]);
        }

        Auth::login($user);
        request()->session()->regenerate();
        return redirect()->intended('/');
    }

    // ====== PARTNER LOGIN ======
    public function showLoginPartner()
    {
        return view('auth.login-partner');
    }

    public function loginPartner(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role !== 'partner') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun ini bukan akun Partner. Silakan gunakan login yang sesuai.']);
            }

            $request->session()->regenerate();
            return redirect()->route('partner.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // ====== PARTNER REGISTER ======
    public function showRegisterPartner()
    {
        return view('auth.register-partner');
    }

    public function registerPartner(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email',
            'category'     => 'required|string|max:255',
            'website_url'  => 'nullable|url|max:255',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        // 1. Create User with role 'partner'
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'partner',
        ]);

        // 2. Create Partner model linked to User
        Partner::create([
            'user_id'     => $user->id,
            'name'        => $data['name'],
            'category'    => $data['category'],
            'website_url' => $data['website_url'] ?? null,
            'status'      => 'Aktif',
        ]);

        // 3. Login partner automatically & redirect to dashboard
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('partner.dashboard')->with('success', 'Pendaftaran Akun Partner berhasil! Selamat datang di Dashboard Partner.');
    }

    // ====== CUSTOMER REGISTER ======
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user',
        ]);

        Auth::login($user);
        return redirect('/');
    }

    // ====== LOGOUT (shared) ======
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

