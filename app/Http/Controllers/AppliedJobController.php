<?php

namespace App\Http\Controllers;

use App\Models\AppliedJob;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class AppliedJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appliedJobs = AppliedJob::with(['user', 'job'])->get();
        return view('applied-jobs.index', compact('appliedJobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'user')->get();
        $jobs = Job::all();
        return view('applied-jobs.create', compact('users', 'jobs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs_listing,id',
            'status' => 'required|in:pending,reviewed,interview,accepted,rejected,cancelled',
        ]);

        // Check if already applied
        $exists = AppliedJob::where('user_id', $validated['user_id'])
            ->where('job_id', $validated['job_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'User sudah melamar pekerjaan ini!');
        }

        AppliedJob::create($validated);

        return redirect()->route('applied-jobs.index')->with('success', 'Lamaran berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AppliedJob $appliedJob)
    {
        return view('applied-jobs.show', compact('appliedJob'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppliedJob $appliedJob)
    {
        $users = User::where('role', 'user')->get();
        $jobs = Job::all();
        return view('applied-jobs.edit', compact('appliedJob', 'users', 'jobs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppliedJob $appliedJob)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs_listing,id',
            'status' => 'required|in:pending,reviewed,interview,accepted,rejected,cancelled',
        ]);

        // Check if already applied (excluding current record)
        $exists = AppliedJob::where('user_id', $validated['user_id'])
            ->where('job_id', $validated['job_id'])
            ->where('id', '!=', $appliedJob->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'User sudah melamar pekerjaan ini!');
        }

        $appliedJob->update($validated);

        return redirect()->route('applied-jobs.index')->with('success', 'Lamaran berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppliedJob $appliedJob)
    {
        $appliedJob->delete();

        return redirect()->route('applied-jobs.index')->with('success', 'Lamaran berhasil dihapus!');
    }
}
