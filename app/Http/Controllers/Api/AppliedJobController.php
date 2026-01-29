<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppliedJob;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppliedJobController extends Controller
{
    /**
     * Get all applied jobs for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $appliedJobs = AppliedJob::with(['job.user'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar lamaran berhasil diambil',
            'data' => $appliedJobs->map(function ($applied) {
                return [
                    'id' => $applied->id,
                    'job_id' => $applied->job_id,
                    'status' => $applied->status,
                    'job' => $applied->job ? [
                        'id' => $applied->job->id,
                        'title' => $applied->job->title,
                        'nama_perusahaan' => $applied->job->user->nama ?? null,
                        'foto_perusahaan' => $applied->job->user->foto_profile 
                            ? asset('storage/' . $applied->job->user->foto_profile) 
                            : null,
                        'lokasi' => $applied->job->lokasi_kerja,
                        'tipe' => $applied->job->tipe,
                        'gaji' => $applied->job->gaji,
                    ] : null,
                    'applied_at' => $applied->created_at,
                    'updated_at' => $applied->updated_at,
                ];
            }),
        ]);
    }

    /**
     * Apply for a job.
     */
    public function store(Request $request): JsonResponse
    {
        // Cek kelengkapan dokumen user
        $user = $request->user();
        $profile = $user->userProfile ?? null;

        $missing = [];
        if (!$user->foto_profile) $missing[] = 'Foto Profile';
        if (!$user->alamat) $missing[] = 'Alamat';
        if (!$profile) {
            $missing[] = 'CV';
            $missing[] = 'Ijazah Terakhir';
            $missing[] = 'KTP';
        } else {
            if (!$profile->cv) $missing[] = 'CV';
            if (!$profile->ijazah_terakhir) $missing[] = 'Ijazah Terakhir';
            if (!$profile->ktp) $missing[] = 'KTP';
        }

        if (count($missing) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Lengkapi dokumen berikut sebelum melamar pekerjaan!',
                'missing' => $missing
            ], 422);
        }
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs_listing,id',
        ]);

        $userId = $request->user()->id;
        $jobId = $validated['job_id'];

        // Check if user already has an accepted job
        $hasAcceptedJob = AppliedJob::where('user_id', $userId)
            ->where('status', 'accepted')
            ->exists();

        if ($hasAcceptedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah menerima penawaran pekerjaan. Tidak dapat melamar pekerjaan baru.',
            ], 422);
        }

        // Check if user already has 5 applied jobs (excluding cancelled and rejected)
        $appliedCount = AppliedJob::where('user_id', $userId)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->count();

        if ($appliedCount >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mencapai batas maksimal 5 lamaran pekerjaan. Hapus salah satu lamaran untuk melamar pekerjaan baru.',
            ], 422);
        }

        // Check if already applied to this job
        $exists = AppliedJob::where('user_id', $userId)
            ->where('job_id', $jobId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melamar pekerjaan ini sebelumnya.',
            ], 409);
        }

        $appliedJob = AppliedJob::create([
            'user_id' => $userId,
            'job_id' => $jobId,
            'status' => 'pending',
        ]);

        $appliedJob->load('job.user');

        return response()->json([
            'success' => true,
            'message' => 'Lamaran berhasil dikirim',
            'data' => [
                'id' => $appliedJob->id,
                'job_id' => $appliedJob->job_id,
                'status' => $appliedJob->status,
                'job' => $appliedJob->job ? [
                    'id' => $appliedJob->job->id,
                    'title' => $appliedJob->job->title,
                    'nama_perusahaan' => $appliedJob->job->user->nama ?? null,
                    'lokasi' => $appliedJob->job->lokasi_kerja,
                ] : null,
                'applied_at' => $appliedJob->created_at,
            ],
        ], 201);
    }

    /**
     * Get applied job detail.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $appliedJob = AppliedJob::with(['job.user'])
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$appliedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail lamaran berhasil diambil',
            'data' => [
                'id' => $appliedJob->id,
                'job_id' => $appliedJob->job_id,
                'status' => $appliedJob->status,
                'job' => $appliedJob->job ? [
                    'id' => $appliedJob->job->id,
                    'title' => $appliedJob->job->title,
                    'nama_perusahaan' => $appliedJob->job->user->nama ?? null,
                    'foto_perusahaan' => $appliedJob->job->user->foto_profile 
                        ? asset('storage/' . $appliedJob->job->user->foto_profile) 
                        : null,
                    'lokasi' => $appliedJob->job->lokasi_kerja,
                    'tipe' => $appliedJob->job->tipe,
                    'gaji' => $appliedJob->job->gaji,
                    'bidang' => $appliedJob->job->bidang,
                    'deskripsi' => $appliedJob->job->deskripsi,
                ] : null,
                'applied_at' => $appliedJob->created_at,
            ],
        ]);
    }

    /**
     * Cancel/remove an applied job.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $appliedJob = AppliedJob::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$appliedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan',
            ], 404);
        }

        // Optional: Only allow cancellation if status is still pending
        if ($appliedJob->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran yang sudah diproses tidak dapat dibatalkan',
            ], 422);
        }

        $appliedJob->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lamaran berhasil dibatalkan',
        ]);
    }

    /**
     * Check if a job is already applied by the authenticated user.
     */
    public function check(Request $request, $jobId): JsonResponse
    {
        $appliedJob = AppliedJob::where('user_id', $request->user()->id)
            ->where('job_id', $jobId)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'is_applied' => $appliedJob !== null,
                'status' => $appliedJob?->status,
                'applied_id' => $appliedJob?->id,
            ],
        ]);
    }

    /**
     * Get count of applied jobs for the authenticated user.
     */
    public function count(Request $request): JsonResponse
    {
        $count = AppliedJob::where('user_id', $request->user()->id)
            ->whereNotIn('status', ['cancelled', 'rejected', 'accepted'])
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $count,
                'max_limit' => 5,
                'remaining' => max(0, 5 - $count),
            ],
        ]);
    }

    /**
     * Confirm job offer (accept the job)
     */
    public function confirmOffer(Request $request, $id): JsonResponse
    {
        $appliedJob = AppliedJob::with('job')->where('user_id', $request->user()->id)->where('id', $id)->first();

        if (!$appliedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan',
            ], 404);
        }

        if ($appliedJob->status !== 'confirm_accepted') {
            return response()->json([
                'success' => false,
                'message' => 'Status lamaran tidak memungkinkan konfirmasi',
            ], 400);
        }

        // Update status to accepted
        $appliedJob->status = 'accepted';
        $appliedJob->save();

        // Kurangi jumlah lowongan
        $appliedJob->job->decrement('jumlah_lowongan');

        // Batalkan semua lamaran pekerjaan lainnya yang masih aktif
        AppliedJob::where('user_id', $request->user()->id)
            ->where('id', '!=', $id)
            ->whereNotIn('status', ['accepted', 'rejected', 'cancelled'])
            ->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Penawaran pekerjaan berhasil diterima',
            'data' => [
                'id' => $appliedJob->id,
                'status' => $appliedJob->status,
            ],
        ]);
    }

    /**
     * Reject job offer
     */
    public function rejectOffer(Request $request, $id): JsonResponse
    {
        $appliedJob = AppliedJob::where('user_id', $request->user()->id)->where('id', $id)->first();

        if (!$appliedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan',
            ], 404);
        }

        if ($appliedJob->status !== 'confirm_accepted') {
            return response()->json([
                'success' => false,
                'message' => 'Status lamaran tidak memungkinkan penolakan',
            ], 400);
        }

        // Update status to cancelled
        $appliedJob->status = 'cancelled';
        $appliedJob->save();

        return response()->json([
            'success' => true,
            'message' => 'Penawaran pekerjaan berhasil ditolak',
            'data' => [
                'id' => $appliedJob->id,
                'status' => $appliedJob->status,
            ],
        ]);
    }
}
