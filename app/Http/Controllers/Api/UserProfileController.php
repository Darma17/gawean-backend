<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use App\Models\UserProfileCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Get current user profile data
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();
        $user->load(['userProfile.certificates']);

        return response()->json([
            'success' => true,
            'message' => 'Data profile berhasil diambil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'nomor_telepon' => $user->nomor_telepon,
                    'foto_profile' => $user->foto_profile ? asset('storage/' . $user->foto_profile) : null,
                    'alamat' => $user->alamat,
                ],
                'user_profile' => $user->userProfile ? [
                    'id' => $user->userProfile->id,
                    'cv' => $user->userProfile->cv ? asset('storage/' . $user->userProfile->cv) : null,
                    'ijazah_terakhir' => $user->userProfile->ijazah_terakhir ? asset('storage/' . $user->userProfile->ijazah_terakhir) : null,
                    'ktp' => $user->userProfile->ktp ? asset('storage/' . $user->userProfile->ktp) : null,
                    'portofolio' => $user->userProfile->portofolio ? asset('storage/' . $user->userProfile->portofolio) : null,
                    'certificates' => $user->userProfile->certificates->map(function ($cert) {
                        return [
                            'id' => $cert->id,
                            'file_name' => $cert->file_name,
                            'file_url' => asset('storage/' . $cert->file_path),
                        ];
                    }),
                ] : null,
                'has_profile' => $user->userProfile ? true : false,
            ],
        ]);
    }

    /**
     * Update or create user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            // User data
            'nama' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            // User profile data
            'cv' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'ijazah_terakhir' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'portofolio' => 'nullable|string|max:255',
            
            // Certificates (multiple files)
            'sertifikat_pendukung' => 'nullable|array',
            'sertifikat_pendukung.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            
            // Delete certificates
            'delete_certificates' => 'nullable|array',
            'delete_certificates.*' => 'integer|exists:user_profile_certificates,id',
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

        // Get or create user profile
        $userProfile = $user->userProfile;
        $profileData = [];

        // Handle CV upload
        if ($request->hasFile('cv')) {
            if ($userProfile && $userProfile->cv) {
                Storage::disk('public')->delete($userProfile->cv);
            }
            $profileData['cv'] = $request->file('cv')->store('user-profiles/cv', 'public');
        }

        // Handle ijazah upload
        if ($request->hasFile('ijazah_terakhir')) {
            if ($userProfile && $userProfile->ijazah_terakhir) {
                Storage::disk('public')->delete($userProfile->ijazah_terakhir);
            }
            $profileData['ijazah_terakhir'] = $request->file('ijazah_terakhir')->store('user-profiles/ijazah', 'public');
        }

        // Handle KTP upload
        if ($request->hasFile('ktp')) {
            if ($userProfile && $userProfile->ktp) {
                Storage::disk('public')->delete($userProfile->ktp);
            }
            $profileData['ktp'] = $request->file('ktp')->store('user-profiles/ktp', 'public');
        }

        // Handle portofolio upload
        if ($request->hasFile('portofolio')) {
            if ($userProfile && $userProfile->portofolio) {
                Storage::disk('public')->delete($userProfile->portofolio);
            }
            $profileData['portofolio'] = $request->file('portofolio')->store('user-profiles/portofolio', 'public');
        }

        // Create or update user profile
        if ($userProfile) {
            // Update existing profile
            if (!empty($profileData)) {
                $userProfile->update($profileData);
            }
        } else {
            // Create new profile
            $profileData['user_id'] = $user->id;
            $userProfile = UserProfile::create($profileData);
        }

        // Delete selected certificates
        if ($request->has('delete_certificates') && is_array($request->delete_certificates)) {
            foreach ($request->delete_certificates as $certificateId) {
                $certificate = UserProfileCertificate::where('id', $certificateId)
                    ->where('user_profile_id', $userProfile->id)
                    ->first();
                    
                if ($certificate) {
                    Storage::disk('public')->delete($certificate->file_path);
                    $certificate->delete();
                }
            }
        }

        // Handle multiple certificates upload
        if ($request->hasFile('sertifikat_pendukung')) {
            foreach ($request->file('sertifikat_pendukung') as $file) {
                $filePath = $file->store('user-profiles/sertifikat', 'public');
                $userProfile->certificates()->create([
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Reload user with profile
        $user->load(['userProfile.certificates']);

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diupdate',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'nomor_telepon' => $user->nomor_telepon,
                    'foto_profile' => $user->foto_profile ? asset('storage/' . $user->foto_profile) : null,
                    'alamat' => $user->alamat,
                ],
                'user_profile' => $user->userProfile ? [
                    'id' => $user->userProfile->id,
                    'cv' => $user->userProfile->cv ? asset('storage/' . $user->userProfile->cv) : null,
                    'ijazah_terakhir' => $user->userProfile->ijazah_terakhir ? asset('storage/' . $user->userProfile->ijazah_terakhir) : null,
                    'ktp' => $user->userProfile->ktp ? asset('storage/' . $user->userProfile->ktp) : null,
                    'portofolio' => $user->userProfile->portofolio ? asset('storage/' . $user->userProfile->portofolio) : null,
                    'certificates' => $user->userProfile->certificates->map(function ($cert) {
                        return [
                            'id' => $cert->id,
                            'file_name' => $cert->file_name,
                            'file_url' => asset('storage/' . $cert->file_path),
                        ];
                    }),
                ] : null,
            ],
        ]);
    }

    /**
     * Delete a specific certificate
     */
    public function deleteCertificate(Request $request, $id)
    {
        $user = $request->user();
        
        if (!$user->userProfile) {
            return response()->json([
                'success' => false,
                'message' => 'User profile tidak ditemukan',
            ], 404);
        }

        $certificate = UserProfileCertificate::where('id', $id)
            ->where('user_profile_id', $user->userProfile->id)
            ->first();

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan',
            ], 404);
        }

        Storage::disk('public')->delete($certificate->file_path);
        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil dihapus',
        ]);
    }
}
