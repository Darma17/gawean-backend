<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteJob;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoriteJobController extends Controller
{
    /**
     * Get all favorite jobs for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $favoriteJobs = FavoriteJob::with(['job'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar lowongan favorit berhasil diambil',
            'data' => $favoriteJobs->map(function ($favorite) {
                return [
                    'id' => $favorite->id,
                    'job_id' => $favorite->job_id,
                    'job' => $favorite->job,
                    'created_at' => $favorite->created_at,
                ];
            }),
        ]);
    }

    /**
     * Add a job to favorites.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs_listing,id',
        ]);

        $userId = $request->user()->id;
        $jobId = $validated['job_id'];

        // Check if already favorited
        $exists = FavoriteJob::where('user_id', $userId)
            ->where('job_id', $jobId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Lowongan sudah ada di daftar favorit',
            ], 409);
        }

        $favoriteJob = FavoriteJob::create([
            'user_id' => $userId,
            'job_id' => $jobId,
        ]);

        $favoriteJob->load('job');

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil ditambahkan ke favorit',
            'data' => [
                'id' => $favoriteJob->id,
                'job_id' => $favoriteJob->job_id,
                'job' => $favoriteJob->job,
                'created_at' => $favoriteJob->created_at,
            ],
        ], 201);
    }

    /**
     * Remove a job from favorites.
     */
    public function destroy(Request $request, $jobId): JsonResponse
    {
        $favoriteJob = FavoriteJob::where('user_id', $request->user()->id)
            ->where('job_id', $jobId)
            ->first();

        if (!$favoriteJob) {
            return response()->json([
                'success' => false,
                'message' => 'Lowongan tidak ditemukan di daftar favorit',
            ], 404);
        }

        $favoriteJob->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil dihapus dari favorit',
        ]);
    }

    /**
     * Check if a job is favorited by the authenticated user.
     */
    public function check(Request $request, $jobId): JsonResponse
    {
        $isFavorited = FavoriteJob::where('user_id', $request->user()->id)
            ->where('job_id', $jobId)
            ->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'is_favorited' => $isFavorited,
            ],
        ]);
    }

    /**
     * Toggle favorite status for a job.
     */
    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs_listing,id',
        ]);

        $userId = $request->user()->id;
        $jobId = $validated['job_id'];

        $favoriteJob = FavoriteJob::where('user_id', $userId)
            ->where('job_id', $jobId)
            ->first();

        if ($favoriteJob) {
            // Remove from favorites
            $favoriteJob->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lowongan berhasil dihapus dari favorit',
                'data' => [
                    'is_favorited' => false,
                ],
            ]);
        } else {
            // Add to favorites
            $favoriteJob = FavoriteJob::create([
                'user_id' => $userId,
                'job_id' => $jobId,
            ]);

            $favoriteJob->load('job');

            return response()->json([
                'success' => true,
                'message' => 'Lowongan berhasil ditambahkan ke favorit',
                'data' => [
                    'is_favorited' => true,
                    'favorite' => [
                        'id' => $favoriteJob->id,
                        'job_id' => $favoriteJob->job_id,
                        'job' => $favoriteJob->job,
                        'created_at' => $favoriteJob->created_at,
                    ],
                ],
            ], 201);
        }
    }
}
