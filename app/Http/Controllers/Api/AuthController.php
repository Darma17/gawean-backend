<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Login user dengan role = user
     * Mengecek email, password, dan role
     * Jika valid, kirim OTP ke email
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari user berdasarkan email dan role = user
        $user = User::where('email', $request->email)
                    ->where('role', 'user')
                    ->first();

        // Cek apakah user ditemukan
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar atau bukan akun user'
            ], 401);
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        // Cek apakah user aktif
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Silakan hubungi admin.'
            ], 403);
        }

        // Generate OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Simpan OTP ke database dengan waktu kadaluarsa 5 menit
        $user->update([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // Kirim OTP ke email
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->nama));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP ke email. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP telah dikirim ke email Anda',
            'data' => [
                'email' => $user->email,
                'otp_expires_in' => '5 menit'
            ]
        ], 200);
    }

    /**
     * Verifikasi OTP
     * Mengecek kode OTP yang dikirim user
     * Jika valid, kembalikan token untuk autentikasi
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari user berdasarkan email dan role = user
        $user = User::where('email', $request->email)
                    ->where('role', 'user')
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar'
            ], 404);
        }

        // Cek apakah OTP sudah kadaluarsa
        if (!$user->otp_expires_at || Carbon::now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.'
            ], 400);
        }

        // Cek apakah OTP valid
        if (!$user->otp_code || !Hash::check($request->otp, $user->otp_code)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid'
            ], 400);
        }

        // Hapus OTP setelah berhasil diverifikasi
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null
        ]);

        // Hapus token lama jika ada
        $user->tokens()->delete();

        // Buat token baru menggunakan Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'nomor_telepon' => $user->nomor_telepon,
                    'foto_profile' => $user->foto_profile,
                    'alamat' => $user->alamat,
                    'role' => $user->role,
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    /**
     * Kirim ulang OTP
     * Mengirim ulang kode OTP ke email yang sudah terdaftar
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari user berdasarkan email dan role = user
        $user = User::where('email', $request->email)
                    ->where('role', 'user')
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar atau bukan akun user'
            ], 404);
        }

        // Cek apakah user aktif
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Silakan hubungi admin.'
            ], 403);
        }

        // Cek apakah OTP sebelumnya masih valid (untuk mencegah spam)
        if ($user->otp_expires_at && Carbon::now()->isBefore($user->otp_expires_at)) {
            $remainingSeconds = Carbon::now()->diffInSeconds($user->otp_expires_at);
            
            // Jika masih ada waktu lebih dari 4 menit, tolak permintaan
            if ($remainingSeconds > 240) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mohon tunggu sebentar sebelum meminta OTP baru',
                    'data' => [
                        'wait_seconds' => $remainingSeconds - 240
                    ]
                ], 429);
            }
        }

        // Generate OTP 6 digit baru
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Simpan OTP ke database dengan waktu kadaluarsa 5 menit
        $user->update([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // Kirim OTP ke email
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->nama));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP ke email. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP baru telah dikirim ke email Anda',
            'data' => [
                'email' => $user->email,
                'otp_expires_in' => '5 menit'
            ]
        ], 200);
    }

    /**
     * Logout user
     * Menghapus token autentikasi
     */
    public function logout(Request $request)
    {
        // Hapus token saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }

    /**
     * Get user yang sedang login
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'nomor_telepon' => $user->nomor_telepon,
                'foto_profile' => $user->foto_profile,
                'alamat' => $user->alamat,
                'role' => $user->role,
            ]
        ], 200);
    }
}
