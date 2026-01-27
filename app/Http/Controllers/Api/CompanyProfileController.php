<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanyProfileController extends Controller
{
    /**
     * Get current company profile data
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        $user->load(['companyProfile']);

        return response()->json([
            'success' => true,
            'message' => 'Data company profile berhasil diambil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'nomor_telepon' => $user->nomor_telepon,
                    'foto_profile' => $user->foto_profile ? asset('storage/' . $user->foto_profile) : null,
                    'alamat' => $user->alamat,
                ],
                'company_profile' => $user->companyProfile ? [
                    'id' => $user->companyProfile->id,
                    'tahun_berdiri' => $user->companyProfile->tahun_berdiri,
                    'bidang_usaha' => $user->companyProfile->bidang_usaha,
                    'latar_belakang' => $user->companyProfile->latar_belakang,
                    'visi' => $user->companyProfile->visi,
                    'misi' => $user->companyProfile->misi,
                ] : null,
                'has_company_profile' => $user->companyProfile ? true : false,
            ],
        ]);
    }

    /**
     * Update or create company profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            // User data
            'nama' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Company profile data
            'tahun_berdiri' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'bidang_usaha' => 'nullable|string|max:255',
            'latar_belakang' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update user data
        $userData = [];

        if ($request->has('nama')) {
            $userData['nama'] = $request->nama;
        }
        if ($request->has('nomor_telepon')) {
            $userData['nomor_telepon'] = $request->nomor_telepon;
        }
        if ($request->has('alamat')) {
            $userData['alamat'] = $request->alamat;
        }

        // Handle foto profile upload
        if ($request->hasFile('foto_profile')) {
            // Delete old foto if exists
            if ($user->foto_profile) {
                Storage::disk('public')->delete($user->foto_profile);
            }
            $userData['foto_profile'] = $request->file('foto_profile')->store('users/foto-profile', 'public');
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        // Get or create company profile
        $companyProfile = $user->companyProfile;
        $profileData = [];

        if ($request->has('tahun_berdiri')) {
            $profileData['tahun_berdiri'] = $request->tahun_berdiri;
        }
        if ($request->has('bidang_usaha')) {
            $profileData['bidang_usaha'] = $request->bidang_usaha;
        }
        if ($request->has('latar_belakang')) {
            $profileData['latar_belakang'] = $request->latar_belakang;
        }
        if ($request->has('visi')) {
            $profileData['visi'] = $request->visi;
        }
        if ($request->has('misi')) {
            $profileData['misi'] = $request->misi;
        }

        // Create or update company profile
        if ($companyProfile) {
            // Update existing profile
            if (!empty($profileData)) {
                $companyProfile->update($profileData);
            }
        } else {
            // Create new profile
            $profileData['user_id'] = $user->id;
            $companyProfile = CompanyProfile::create($profileData);
        }

        // Reload user with company profile
        $user->load(['companyProfile']);

        return response()->json([
            'success' => true,
            'message' => 'Company profile berhasil diupdate',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'nomor_telepon' => $user->nomor_telepon,
                    'foto_profile' => $user->foto_profile ? asset('storage/' . $user->foto_profile) : null,
                    'alamat' => $user->alamat,
                ],
                'company_profile' => $user->companyProfile ? [
                    'id' => $user->companyProfile->id,
                    'tahun_berdiri' => $user->companyProfile->tahun_berdiri,
                    'bidang_usaha' => $user->companyProfile->bidang_usaha,
                    'latar_belakang' => $user->companyProfile->latar_belakang,
                    'visi' => $user->companyProfile->visi,
                    'misi' => $user->companyProfile->misi,
                ] : null,
            ],
        ]);
    }
}
