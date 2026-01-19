<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput($request->only('email'));
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->withInput($request->only('email'));
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp_code = Hash::make($otp);
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Send OTP via email
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->nama));
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Gagal mengirim kode OTP. Coba lagi nanti.',
            ])->withInput($request->only('email'));
        }

        // Store email in session for OTP verification
        session(['otp_email' => $user->email]);

        return redirect()->route('otp.verify.form')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('login');
        }
        return view('auth.verify-otp');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('login')->withErrors(['otp' => 'Sesi telah berakhir. Silakan login kembali.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['otp' => 'User tidak ditemukan.']);
        }

        if (!$user->otp_code || !$user->otp_expires_at) {
            return redirect()->route('login')->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP telah kadaluarsa. Silakan login kembali.']);
        }

        if (!Hash::check($request->otp, $user->otp_code)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // Clear OTP
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Clear session
        session()->forget('otp_email');

        // Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Login user
        Auth::login($user);

        // Return with token to store in localStorage
        return redirect()->route('admin.dashboard')->with([
            'token' => $token,
            'success' => 'Login berhasil! Selamat datang, ' . $user->nama
        ]);
    }

    /**
     * Resend OTP
     */
    public function resendOtp()
    {
        $email = session('otp_email');
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Sesi telah berakhir.'], 400);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp_code = Hash::make($otp);
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Send OTP via email
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->nama));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim kode OTP.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP baru telah dikirim.']);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        // Revoke all tokens
        if (Auth::check()) {
            $request->user()->tokens()->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
