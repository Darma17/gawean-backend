<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
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
            'sertifikat_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:10240',
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
        if ($request->hasFile('sertifikat_pendukung')) {
            $validated['sertifikat_pendukung'] = $request->file('sertifikat_pendukung')->store('user-profiles/sertifikat', 'public');
        }

        UserProfile::create($validated);

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
            'sertifikat_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:10240',
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
        if ($request->hasFile('sertifikat_pendukung')) {
            if ($userProfile->sertifikat_pendukung) Storage::disk('public')->delete($userProfile->sertifikat_pendukung);
            $validated['sertifikat_pendukung'] = $request->file('sertifikat_pendukung')->store('user-profiles/sertifikat', 'public');
        }

        $userProfile->update($validated);

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
        if ($userProfile->sertifikat_pendukung) Storage::disk('public')->delete($userProfile->sertifikat_pendukung);

        $userProfile->delete();

        return redirect()->route('user-profiles.index')->with('success', 'Profile berhasil dihapus!');
    }
}
