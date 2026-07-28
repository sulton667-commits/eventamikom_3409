<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    // ====== REGISTER ======
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
