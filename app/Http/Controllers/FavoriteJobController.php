<?php

namespace App\Http\Controllers;

use App\Models\FavoriteJob;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favoriteJobs = FavoriteJob::with(['user', 'job'])->get();
        return view('favorite-jobs.index', compact('favoriteJobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'user')->get();
        $jobs = Job::all();
        return view('favorite-jobs.create', compact('users', 'jobs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs_listing,id',
        ]);

        // Check if already favorited
        $exists = FavoriteJob::where('user_id', $validated['user_id'])
            ->where('job_id', $validated['job_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Job sudah ada di favorit!');
        }

        FavoriteJob::create($validated);

        return redirect()->route('favorite-jobs.index')->with('success', 'Job berhasil ditambahkan ke favorit!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FavoriteJob $favoriteJob)
    {
        return view('favorite-jobs.show', compact('favoriteJob'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FavoriteJob $favoriteJob)
    {
        $users = User::where('role', 'user')->get();
        $jobs = Job::all();
        return view('favorite-jobs.edit', compact('favoriteJob', 'users', 'jobs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FavoriteJob $favoriteJob)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs_listing,id',
        ]);

        // Check if already favorited (excluding current record)
        $exists = FavoriteJob::where('user_id', $validated['user_id'])
            ->where('job_id', $validated['job_id'])
            ->where('id', '!=', $favoriteJob->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Job sudah ada di favorit user lain!');
        }

        $favoriteJob->update($validated);

        return redirect()->route('favorite-jobs.index')->with('success', 'Favorit berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FavoriteJob $favoriteJob)
    {
        $favoriteJob->delete();

        return redirect()->route('favorite-jobs.index')->with('success', 'Job berhasil dihapus dari favorit!');
    }
}
