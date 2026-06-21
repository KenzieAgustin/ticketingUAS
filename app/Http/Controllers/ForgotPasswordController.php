<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $otp = rand(100000, 999999);

        Otp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        Mail::to($request->email)->send(new OtpMail($otp));

        return redirect()->route('password.verify-otp.show', ['email' => $request->email])
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    public function showVerifyOtp(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'digits:6'],
        ]);

        $record = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        if (now()->greaterThan($record->expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.']);
        }

        return redirect()->route('password.reset.show', ['email' => $request->email]);
    }

    public function showReset(Request $request)
    {
        return view('auth.reset-password', ['email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        Otp::where('email', $request->email)->delete();

        UserActivity::create([
            'user_id'    => $user->id,
            'action'     => 'reset_password',
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login.');
    }
}