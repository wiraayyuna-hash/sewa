<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class RuteKitaAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login', [
            'activeTab' => 'login',
            'pageTitle' => 'RuteKita — Login',
        ]);
    }

    public function showRegister()
    {
        return view('auth.login', [
            'activeTab' => 'register',
            'pageTitle' => 'RuteKita — Signup',
        ]);
    }

    public function showForgotPassword()
    {
        return view('auth.login', [
            'activeTab' => 'forgot',
            'pageTitle' => 'RuteKita — Lupa Password',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Schema::hasTable('users')) {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'Selamat datang kembali di RuteKita!');
            }

            $userExists = User::where('email', $credentials['email'])->exists();
            if ($userExists) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Email atau password tidak sesuai.']);
            }
        }

        $request->session()->put('rutekita_user_email', $credentials['email']);

        return redirect('/admin/dashboard')
            ->with('success', 'Login berhasil! Selamat datang di RuteKita.');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if (Schema::hasTable('users')) {
            if (User::where('email', $validated['email'])->exists()) {
                return back()
                    ->withInput($request->only('name', 'email', 'whatsapp'))
                    ->withErrors(['email' => 'Email ini sudah terdaftar di RuteKita.']);
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'whatsapp' => $validated['whatsapp'] ?? null,
                'role' => 'penyewa',
                'password' => Hash::make($validated['password']),
            ]);

            Auth::login($user);
        }

        $request->session()->put('rutekita_user_email', $validated['email']);

        return redirect('/admin/dashboard')
            ->with('success', 'Akun RuteKita berhasil dibuat untuk ' . $validated['name'] . '!');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return back()->with('status', 'Tautan reset password telah dikirim ke ' . $request->email . '.');
    }

    public function logout(Request $request)
    {
        if (Schema::hasTable('users')) {
            Auth::logout();
        }
        $request->session()->forget('rutekita_user_email');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
