<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\UserProfileCertificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userProfiles = UserProfile::with('user')->get();
        return view('user-profiles.index', compact('userProfiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'user')->doesntHave('userProfile')->get();
        return view('user-profiles.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:user_profiles,user_id',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'ijazah_terakhir' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'portofolio' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
            'sertifikat_pendukung' => 'nullable|array',
            'sertifikat_pendukung.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('cv')) {
            $validated['cv'] = $request->file('cv')->store('user-profiles/cv', 'public');
        }
        if ($request->hasFile('ijazah_terakhir')) {
            $validated['ijazah_terakhir'] = $request->file('ijazah_terakhir')->store('user-profiles/ijazah', 'public');
        }
        if ($request->hasFile('ktp')) {
            $validated['ktp'] = $request->file('ktp')->store('user-profiles/ktp', 'public');
        }
        if ($request->hasFile('portofolio')) {
            $validated['portofolio'] = $request->file('portofolio')->store('user-profiles/portofolio', 'public');
        }

        // Remove sertifikat_pendukung from validated data before creating profile
        unset($validated['sertifikat_pendukung']);

        $userProfile = UserProfile::create($validated);

        // Handle multiple certificates
        if ($request->hasFile('sertifikat_pendukung')) {
            foreach ($request->file('sertifikat_pendukung') as $file) {
                $filePath = $file->store('user-profiles/sertifikat', 'public');
                $userProfile->certificates()->create([
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('user-profiles.index')->with('success', 'Profile berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserProfile $userProfile)
    {
        return view('user-profiles.show', compact('userProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
        return view('user-profiles.edit', compact('userProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserProfile $userProfile)
    {
        $validated = $request->validate([
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'ijazah_terakhir' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'portofolio' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
            'sertifikat_pendukung' => 'nullable|array',
            'sertifikat_pendukung.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'delete_certificates' => 'nullable|array',
            'delete_certificates.*' => 'exists:user_profile_certificates,id',
        ]);

        if ($request->hasFile('cv')) {
            if ($userProfile->cv) Storage::disk('public')->delete($userProfile->cv);
            $validated['cv'] = $request->file('cv')->store('user-profiles/cv', 'public');
        }
        if ($request->hasFile('ijazah_terakhir')) {
            if ($userProfile->ijazah_terakhir) Storage::disk('public')->delete($userProfile->ijazah_terakhir);
            $validated['ijazah_terakhir'] = $request->file('ijazah_terakhir')->store('user-profiles/ijazah', 'public');
        }
        if ($request->hasFile('ktp')) {
            if ($userProfile->ktp) Storage::disk('public')->delete($userProfile->ktp);
            $validated['ktp'] = $request->file('ktp')->store('user-profiles/ktp', 'public');
        }
        if ($request->hasFile('portofolio')) {
            if ($userProfile->portofolio) Storage::disk('public')->delete($userProfile->portofolio);
            $validated['portofolio'] = $request->file('portofolio')->store('user-profiles/portofolio', 'public');
        }

        // Remove array fields before updating profile
        unset($validated['sertifikat_pendukung']);
        unset($validated['delete_certificates']);

        $userProfile->update($validated);

        // Delete selected certificates
        if ($request->has('delete_certificates')) {
            foreach ($request->delete_certificates as $certificateId) {
                $certificate = UserProfileCertificate::find($certificateId);
                if ($certificate && $certificate->user_profile_id == $userProfile->id) {
                    Storage::disk('public')->delete($certificate->file_path);
                    $certificate->delete();
                }
            }
        }

        // Handle new certificates upload
        if ($request->hasFile('sertifikat_pendukung')) {
            foreach ($request->file('sertifikat_pendukung') as $file) {
                $filePath = $file->store('user-profiles/sertifikat', 'public');
                $userProfile->certificates()->create([
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('user-profiles.index')->with('success', 'Profile berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserProfile $userProfile)
    {
        if ($userProfile->cv) Storage::disk('public')->delete($userProfile->cv);
        if ($userProfile->ijazah_terakhir) Storage::disk('public')->delete($userProfile->ijazah_terakhir);
        if ($userProfile->ktp) Storage::disk('public')->delete($userProfile->ktp);
        if ($userProfile->portofolio) Storage::disk('public')->delete($userProfile->portofolio);

        // Delete all certificates
        foreach ($userProfile->certificates as $certificate) {
            Storage::disk('public')->delete($certificate->file_path);
        }

        $userProfile->delete();

        return redirect()->route('user-profiles.index')->with('success', 'Profile berhasil dihapus!');
    }
}
