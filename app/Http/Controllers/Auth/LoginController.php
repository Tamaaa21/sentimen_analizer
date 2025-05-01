<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

   // In App\Http\Controllers\Auth\LoginController.php
public function login(Request $request)
{
    // ... existing validation code ...

    $credentials = $request->only('email', 'password');
    $remember = $request->filled('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    // ... error handling code ...
}

// In App\Http\Controllers\Auth\RegisterController.php
public function register(Request $request)
{
    // ... existing validation and user creation code ...

    auth()->login($user);

    return redirect()->route('home')->with('success', 'Akun berhasil dibuat dan Anda telah login.');
}

// In App\Http\Controllers\Auth\LogoutController.php
public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
}}