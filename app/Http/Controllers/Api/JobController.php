<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\AppliedJob;
use App\Mail\InterviewInvitation;
use App\Mail\RejectionNotification;
use App\Mail\JobAcceptance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


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
                'total_applicants' => $job->appliedJobs->whereNotIn('status', ['cancelled', 'rejected', 'accepted'])->count(),
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
     * Get detailed information for a specific job created by the authenticated company
     */
    public function getJobDetailCompany(Request $request, $jobId)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        // Cek apakah job milik perusahaan ini
        $job = Job::where('id', $jobId)
            ->where('user_id', $user->id)
            ->with(['appliedJobs.user'])
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Pekerjaan tidak ditemukan atau tidak milik perusahaan Anda.',
            ], 404);
        }

        $data = [
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
            'created_at' => $job->created_at,
            'updated_at' => $job->updated_at
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail pekerjaan berhasil diambil',
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
            $applicantCount = $job->appliedJobs->whereNotIn('status', ['cancelled', 'rejected', 'accepted'])->count();
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

    /**
     * Create a new job for the authenticated company
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }


        // Cek apakah company profile sudah lengkap
        $companyProfile = $user->companyProfile;
        if (!$companyProfile || !$companyProfile->tahun_berdiri || !$companyProfile->bidang_usaha) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile belum lengkap. Silakan lengkapi profile perusahaan terlebih dahulu.',
            ], 400);
        }

        // Cek apakah sudah mengupload 5 pekerjaan
        $jobCount = Job::where('user_id', $user->id)->count();
        if ($jobCount >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal 5 pekerjaan yang dapat diupload. Hapus pekerjaan lama untuk menambah yang baru.',
            ], 400);
        }

        // Validasi input
        $validated = $request->validate([
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

        // Buat job baru
        $job = Job::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'lokasi_kerja' => $validated['lokasi_kerja'],
            'tipe' => $validated['tipe'],
            'jumlah_lowongan' => $validated['jumlah_lowongan'],
            'bidang' => $validated['bidang'],
            'skill_yang_dibutuhkan' => $validated['skill_yang_dibutuhkan'],
            'gaji' => $validated['gaji'],
            'jadwal_kerja' => $validated['jadwal_kerja'],
            'jam_kerja' => $validated['jam_kerja'],
            'deskripsi' => $validated['deskripsi'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pekerjaan berhasil ditambahkan',
            'data' => $job,
        ], 201);
    }

    /**
     * Get applicants for a specific job created by the authenticated company
     */
    public function getJobApplicants(Request $request, $jobId)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        // Cek apakah job milik perusahaan ini
        $job = Job::where('id', $jobId)
            ->where('user_id', $user->id)
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Pekerjaan tidak ditemukan atau tidak milik perusahaan Anda.',
            ], 404);
        }

        // Ambil pelamar dengan data dasar (kecuali yang rejected, cancelled, dan accepted)
        $applicants = AppliedJob::with('user')
            ->where('job_id', $jobId)
            ->whereNotIn('status', ['rejected', 'cancelled', 'accepted'])
            ->get();

        $data = $applicants->map(function ($applied) {
            return [
                'id' => $applied->id,
                'user_id' => $applied->user_id,
                'nama' => $applied->user->nama,
                'email' => $applied->user->email,
                'nomor_telepon' => $applied->user->nomor_telepon,
                'foto_profile' => $applied->user->foto_profile ? asset('storage/' . $applied->user->foto_profile) : null,
                'status' => $applied->status,
                'applied_at' => $applied->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data pelamar berhasil diambil',
            'data' => $data,
        ]);
    }

    /**
     * Get detailed applicant information for a specific job
     */
    public function getApplicantDetail(Request $request, $jobId, $applicantId)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        // Cek apakah job milik perusahaan ini
        $job = Job::where('id', $jobId)
            ->where('user_id', $user->id)
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Pekerjaan tidak ditemukan atau tidak milik perusahaan Anda.',
            ], 404);
        }

        // Ambil applied job dengan data lengkap
        $appliedJob = AppliedJob::with(['user.userProfile.certificates'])
            ->where('id', $applicantId)
            ->where('job_id', $jobId)
            ->first();

        if (!$appliedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Pelamar tidak ditemukan untuk pekerjaan ini.',
            ], 404);
        }

        $applicant = $appliedJob->user;
        $profile = $applicant->userProfile;

        $data = [
            'id' => $appliedJob->id,
            'user_id' => $applicant->id,
            'nama' => $applicant->nama,
            'email' => $applicant->email,
            'nomor_telepon' => $applicant->nomor_telepon,
            'alamat' => $applicant->alamat,
            'foto_profile' => $applicant->foto_profile ? asset('storage/' . $applicant->foto_profile) : null,
            'status' => $appliedJob->status,
            'applied_at' => $appliedJob->created_at,
            'interview_details' => $appliedJob->status === 'interview' || $appliedJob->status === 'confirm_accepted' || $appliedJob->status === 'accepted' ? [
                'date' => $appliedJob->interview_date,
                'time' => $appliedJob->interview_time,
                'link' => $appliedJob->interview_link,
                'message' => $appliedJob->interview_message,
            ] : null,
            'profile' => $profile ? [
                'cv' => $profile->cv ? asset('storage/' . $profile->cv) : null,
                'ijazah_terakhir' => $profile->ijazah_terakhir ? asset('storage/' . $profile->ijazah_terakhir) : null,
                'ktp' => $profile->ktp ? asset('storage/' . $profile->ktp) : null,
                'portfolio_link' => $profile->portfolio_link,
                'certificates' => $profile->certificates->map(function ($cert) {
                    return [
                        'id' => $cert->id,
                        'nama_sertifikat' => $cert->nama_sertifikat,
                        'file' => $cert->file ? asset('storage/' . $cert->file) : null,
                        'created_at' => $cert->created_at,
                    ];
                }),
            ] : null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail pelamar berhasil diambil',
            'data' => $data,
        ]);
    }

    /**
     * Update applicant status for a specific job
     */
    public function updateApplicantStatus(Request $request, $jobId, $applicantId)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        // Cek apakah job milik perusahaan ini
        $job = Job::where('id', $jobId)
            ->where('user_id', $user->id)
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Pekerjaan tidak ditemukan atau tidak milik perusahaan Anda.',
            ], 404);
        }

        // Ambil applied job
        $appliedJob = AppliedJob::with(['user', 'job.user'])
            ->where('id', $applicantId)
            ->where('job_id', $jobId)
            ->first();

        if (!$appliedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Pelamar tidak ditemukan untuk pekerjaan ini.',
            ], 404);
        }

        $currentStatus = $appliedJob->status;
        $newStatus = null;
        $sendEmail = false;
        $emailType = null; // 'interview' or 'acceptance'

        // Logic untuk mengubah status
        switch ($currentStatus) {
            case 'pending':
                $newStatus = 'reviewed';
                break;
            case 'reviewed':
                // Validasi input untuk interview
                $validated = $request->validate([
                    'interview_date' => 'required|date|after:today',
                    'interview_time' => 'required|date_format:H:i',
                    'interview_link' => 'required|url',
                    'interview_message' => 'nullable|string|max:1000',
                ]);

                $newStatus = 'interview';
                $sendEmail = true;
                $emailType = 'interview';

                // Simpan detail interview
                $appliedJob->interview_date = $validated['interview_date'];
                $appliedJob->interview_time = $validated['interview_time'];
                $appliedJob->interview_link = $validated['interview_link'];
                $appliedJob->interview_message = $validated['interview_message'];
                break;
            case 'interview':
                $newStatus = 'confirm_accepted';
                $sendEmail = true;
                $emailType = 'acceptance';
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak dapat diubah lagi.',
                ], 400);
        }

        // Update status
        $appliedJob->status = $newStatus;
        $appliedJob->save();

        // Kirim email berdasarkan tipe
        if ($sendEmail) {
            try {
                if ($emailType === 'interview') {
                    Mail::to($appliedJob->user->email)->send(new InterviewInvitation($appliedJob));
                } elseif ($emailType === 'acceptance') {
                    Mail::to($appliedJob->user->email)->send(new JobAcceptance($appliedJob));
                }
            } catch (\Exception $e) {
                // Log error tapi jangan gagal request
                Log::error('Failed to send email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pelamar berhasil diperbarui',
            'data' => [
                'id' => $appliedJob->id,
                'user_id' => $appliedJob->user_id,
                'nama' => $appliedJob->user->nama,
                'email' => $appliedJob->user->email,
                'status' => $appliedJob->status,
                'applied_at' => $appliedJob->created_at,
            ],
        ]);
    }

    /**
     * Reject an applicant for a specific job
     */
    public function rejectApplicant(Request $request, $jobId, $applicantId)
    {
        $user = $request->user();

        // Pastikan user adalah perusahaan
        if ($user->role !== 'perusahaan') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya perusahaan yang dapat mengakses.',
            ], 403);
        }

        // Cek apakah job milik perusahaan ini
        $job = Job::where('id', $jobId)
            ->where('user_id', $user->id)
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Pekerjaan tidak ditemukan atau tidak milik perusahaan Anda.',
            ], 404);
        }

        // Ambil applied job
        $appliedJob = AppliedJob::with(['user', 'job.user'])
            ->where('id', $applicantId)
            ->where('job_id', $jobId)
            ->first();

        if (!$appliedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Pelamar tidak ditemukan untuk pekerjaan ini.',
            ], 404);
        }

        // Cek apakah sudah rejected
        if ($appliedJob->status === 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Pelamar ini sudah ditolak sebelumnya.',
            ], 400);
        }

        // Update status ke rejected
        $appliedJob->status = 'rejected';
        $appliedJob->save();

        // Kirim email penolakan
        try {
            Mail::to($appliedJob->user->email)->send(new RejectionNotification($appliedJob));
        } catch (\Exception $e) {
            // Log error tapi jangan gagal request
            Log::error('Failed to send rejection notification email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pelamar berhasil ditolak dan email pemberitahuan telah dikirim',
            'data' => [
                'id' => $appliedJob->id,
                'user_id' => $appliedJob->user_id,
                'nama' => $appliedJob->user->nama,
                'email' => $appliedJob->user->email,
                'status' => $appliedJob->status,
                'applied_at' => $appliedJob->created_at,
            ],
        ]);
    }
}
