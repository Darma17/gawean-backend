<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Login user (user/perusahaan) tanpa perlu mengirim role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ditemukan
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar'
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
                'otp_expires_in' => '5 menit',
                'role' => $user->role
            ]
        ], 200);
    }

    /**
     * Login user dengan role = perusahaan (company)
     * Mengecek email, password, dan role
     * Jika valid, kirim OTP ke email
     */
    public function loginCompany(Request $request)
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

        // Cari user berdasarkan email dan role = perusahaan
        $user = User::where('email', $request->email)
                    ->where('role', 'perusahaan')
                    ->first();

        // Cek apakah user ditemukan
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar atau bukan akun perusahaan'
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
                'otp_expires_in' => '5 menit',
                'role' => 'perusahaan'
            ]
        ], 200);
    }

    /**
     * Verifikasi OTP
     * Mengecek kode OTP yang dikirim user
     * Jika valid, kembalikan token untuk autentikasi dan data user beserta rolenya
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

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

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

    /**
     * Register user baru (role user), kirim OTP ke email
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_telepon' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Buat user baru dengan role user
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'password' => bcrypt($request->password),
            'alamat' => $request->alamat,
            'role' => 'user',
            'is_active' => true,
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => Carbon::now()->addMinutes(5),
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
            'message' => 'Registrasi berhasil. OTP telah dikirim ke email Anda',
            'data' => [
                'email' => $user->email,
                'otp_expires_in' => '5 menit',
                'role' => $user->role
            ]
        ], 201);
    }

    /**
     * Forgot password - kirim OTP ke email
     */
    public function forgotPassword(Request $request)
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

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar'
            ], 404);
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpHash = Hash::make($otp);
        $expiresAt = Carbon::now()->addMinutes(5);

        // Simpan OTP di cache untuk reset password
        Cache::put('password_reset_' . $request->email, [
            'otp' => $otpHash,
            'expires_at' => $expiresAt,
        ], $expiresAt);

        // Kirim email OTP
        try {
            Mail::to($user->email)->send(new OtpMail($otp, 'reset'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email OTP. Silakan coba lagi.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ke email Anda',
            'data' => [
                'email' => $user->email,
                'otp_expires_in' => '5 menit'
            ]
        ]);
    }

    /**
     * Verifikasi OTP untuk reset password
     */
    public function verifyResetOtp(Request $request)
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

        // Ambil data OTP dari cache
        $resetData = Cache::get('password_reset_' . $request->email);

        if (!$resetData) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak ditemukan. Silakan minta kode baru.'
            ], 400);
        }

        // Cek apakah OTP sudah kadaluarsa
        if (Carbon::now()->isAfter($resetData['expires_at'])) {
            Cache::forget('password_reset_' . $request->email);
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.'
            ], 400);
        }

        // Cek apakah OTP valid
        if (!Hash::check($request->otp, $resetData['otp'])) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid'
            ], 400);
        }

        // Tandai bahwa OTP sudah diverifikasi untuk reset password
        Cache::put('password_reset_verified_' . $request->email, true, now()->addMinutes(15));

        // Hapus data OTP
        Cache::forget('password_reset_' . $request->email);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil diverifikasi. Silakan atur password baru.',
            'data' => [
                'email' => $request->email,
                'reset_token_valid_until' => now()->addMinutes(15)->toISOString()
            ]
        ]);
    }

    /**
     * Reset password setelah verifikasi OTP
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek apakah OTP sudah diverifikasi
        $isVerified = Cache::get('password_reset_verified_' . $request->email);

        if (!$isVerified) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi OTP diperlukan sebelum mengatur password baru'
            ], 400);
        }

        // Cari user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus flag verifikasi
        Cache::forget('password_reset_verified_' . $request->email);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah. Silakan login dengan password baru.'
        ]);
    }
}
