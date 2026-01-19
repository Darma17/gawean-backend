<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::with('user')->get();
        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = User::where('role', 'perusahaan')->get();
        return view('jobs.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'lokasi_kerja' => 'required|string|max:255',
            'tipe' => 'required|in:onsite,remote',
            'jumlah_lowongan' => 'required|integer|min:1',
            'bidang' => 'required|string|max:255',
            'skill_yang_dibutuhkan' => 'required|string',
            'gaji' => 'nullable|string|max:255',
            'jadwal_kerja' => 'nullable|string|max:255',
            'jam_kerja' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Job::create($validated);

        return redirect()->route('jobs.index')->with('success', 'Lowongan kerja berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        $companies = User::where('role', 'perusahaan')->get();
        return view('jobs.edit', compact('job', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'lokasi_kerja' => 'required|string|max:255',
            'tipe' => 'required|in:onsite,remote',
            'jumlah_lowongan' => 'required|integer|min:1',
            'bidang' => 'required|string|max:255',
            'skill_yang_dibutuhkan' => 'required|string',
            'gaji' => 'nullable|string|max:255',
            'jadwal_kerja' => 'nullable|string|max:255',
            'jam_kerja' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $job->update($validated);

        return redirect()->route('jobs.index')->with('success', 'Lowongan kerja berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Lowongan kerja berhasil dihapus!');
    }
}
