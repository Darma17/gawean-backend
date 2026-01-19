<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyProfiles = CompanyProfile::with('user')->get();
        return view('company-profiles.index', compact('companyProfiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = User::where('role', 'perusahaan')->doesntHave('companyProfile')->get();
        return view('company-profiles.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:company_profiles,user_id',
            'tahun_berdiri' => 'nullable|integer|min:1800|max:' . date('Y'),
            'bidang_usaha' => 'nullable|string|max:255',
            'latar_belakang' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        CompanyProfile::create($validated);

        return redirect()->route('company-profiles.index')->with('success', 'Company Profile berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyProfile $companyProfile)
    {
        return view('company-profiles.show', compact('companyProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyProfile $companyProfile)
    {
        return view('company-profiles.edit', compact('companyProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyProfile $companyProfile)
    {
        $validated = $request->validate([
            'tahun_berdiri' => 'nullable|integer|min:1800|max:' . date('Y'),
            'bidang_usaha' => 'nullable|string|max:255',
            'latar_belakang' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        $companyProfile->update($validated);

        return redirect()->route('company-profiles.index')->with('success', 'Company Profile berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyProfile $companyProfile)
    {
        $companyProfile->delete();

        return redirect()->route('company-profiles.index')->with('success', 'Company Profile berhasil dihapus!');
    }
}
