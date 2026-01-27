<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Get all unique cities from jobs
     */
    public function getCities()
    {
        $cities = Job::select('lokasi_kerja')
            ->distinct()
            ->whereNotNull('lokasi_kerja')
            ->orderBy('lokasi_kerja')
            ->pluck('lokasi_kerja');

        return response()->json([
            'success' => true,
            'message' => 'Data kota berhasil diambil',
            'data' => $cities,
        ]);
    }

    /**
     * Get all jobs with company info
     */
    public function index()
    {
        $jobs = Job::with('user')->get();

        $data = $jobs->map(function ($job) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'nama_perusahaan' => $job->user->nama,
                'foto_perusahaan' => $job->user->foto_profile ? asset('storage/' . $job->user->foto_profile) : null,
                'tipe' => $job->tipe,
                'lokasi' => $job->lokasi_kerja,
                'gaji' => $job->gaji,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data lowongan berhasil diambil',
            'data' => $data,
        ]);
    }

    /**
     * Get count of jobs created by the authenticated company
     */
    public function countCompanyJobs(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        $count = Job::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'message' => 'Jumlah pekerjaan berhasil dihitung',
            'data' => [
                'total_jobs' => $count,
            ],
        ]);
    }

    /**
     * Get all jobs created by the authenticated company
     */
    public function getCompanyJobs(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        $jobs = Job::where('user_id', $user->id)
            ->with(['appliedJobs'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $jobs->map(function ($job) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'lokasi_kerja' => $job->lokasi_kerja,
                'tipe' => $job->tipe,
                'jumlah_lowongan' => $job->jumlah_lowongan,
                'bidang' => $job->bidang,
                'skill_yang_dibutuhkan' => $job->skill_yang_dibutuhkan,
                'gaji' => $job->gaji,
                'jadwal_kerja' => $job->jadwal_kerja,
                'jam_kerja' => $job->jam_kerja,
                'deskripsi' => $job->deskripsi,
                'total_applicants' => $job->appliedJobs->count(),
                'created_at' => $job->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data pekerjaan perusahaan berhasil diambil',
            'data' => $data,
        ]);
    }

    /**
     * Get count of applicants for all jobs created by the authenticated company
     */
    public function countJobApplicants(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        // Get all jobs created by this company
        $jobs = Job::where('user_id', $user->id)->with('appliedJobs')->get();

        $totalApplicants = 0;
        $jobApplicants = [];

        foreach ($jobs as $job) {
            $applicantCount = $job->appliedJobs->count();
            $totalApplicants += $applicantCount;

            $jobApplicants[] = [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'applicant_count' => $applicantCount,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Jumlah pelamar berhasil dihitung',
            'data' => [
                'total_applicants' => $totalApplicants,
                'total_jobs' => $jobs->count(),
                'job_applicants' => $jobApplicants,
            ],
        ]);
    }
}
