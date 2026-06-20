<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\OtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $otp = rand(100000, 999999);

        PasswordResetOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp'        => $otp,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        Mail::to($request->email)->send(new OtpMail($otp, 'register'));

        return redirect()->route('register.verify-otp.show', ['email' => $request->email])
            ->with('success', 'Registrasi berhasil! Kode OTP telah dikirim ke email kamu.');
    }

    public function showVerifyRegisterOtp(Request $request)
    {
        return view('auth.verify-register-otp', ['email' => $request->email]);
    }

    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'digits:6'],
        ]);

        $record = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        if (now()->greaterThan($record->expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['email_verified_at' => now()]);

        PasswordResetOtp::where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }

    public function resendRegisterOtp(Request $request)
    {
        $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

        $otp = rand(100000, 999999);

        PasswordResetOtp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        Mail::to($request->email)->send(new OtpMail($otp, 'register'));

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        $user = User::where('email', $request->email)->first();

        if ($user && !$user->email_verified_at) {
            return back()->withErrors([
                'email' => 'Email belum diverifikasi. Silakan cek email kamu.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            UserActivity::create([
                'user_id'    => Auth::id(),
                'action'     => 'login',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        UserActivity::create([
            'user_id'    => Auth::id(),
            'action'     => 'logout',
            'ip_address' => $request->ip(),
        ]);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}