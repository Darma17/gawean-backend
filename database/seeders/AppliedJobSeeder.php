<?php

namespace Database\Seeders;

use App\Models\AppliedJob;
use Illuminate\Database\Seeder;

class AppliedJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Budi Santoso (user_id: 5) applications
        AppliedJob::create([
            'user_id' => 5,
            'job_id' => 1, // Full Stack Developer
            'status' => 'interview',
        ]);

        AppliedJob::create([
            'user_id' => 5,
            'job_id' => 3, // DevOps Engineer
            'status' => 'pending',
        ]);

        // Siti Rahayu (user_id: 6) applications
        AppliedJob::create([
            'user_id' => 6,
            'job_id' => 2, // UI/UX Designer
            'status' => 'reviewed',
        ]);

        AppliedJob::create([
            'user_id' => 6,
            'job_id' => 5, // Graphic Designer
            'status' => 'accepted',
        ]);

        // Ahmad Hidayat (user_id: 7) applications
        AppliedJob::create([
            'user_id' => 7,
            'job_id' => 4, // Digital Marketing Specialist
            'status' => 'pending',
        ]);

        AppliedJob::create([
            'user_id' => 7,
            'job_id' => 6, // Content Writer
            'status' => 'reviewed',
        ]);

        // Dewi Lestari (user_id: 8) applications
        AppliedJob::create([
            'user_id' => 8,
            'job_id' => 10, // HRD Staff
            'status' => 'rejected',
        ]);

        AppliedJob::create([
            'user_id' => 8,
            'job_id' => 7, // Production Supervisor
            'status' => 'pending',
        ]);

        // Rudi Hermawan (user_id: 9) applications
        AppliedJob::create([
            'user_id' => 9,
            'job_id' => 8, // Warehouse Staff
            'status' => 'interview',
        ]);
    }
}
