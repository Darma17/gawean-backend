<?php

namespace Database\Seeders;

use App\Models\FavoriteJob;
use Illuminate\Database\Seeder;

class FavoriteJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Budi Santoso (user_id: 5) favorites
        FavoriteJob::create([
            'user_id' => 5,
            'job_id' => 1, // Full Stack Developer
        ]);

        FavoriteJob::create([
            'user_id' => 5,
            'job_id' => 3, // DevOps Engineer
        ]);

        // Siti Rahayu (user_id: 6) favorites
        FavoriteJob::create([
            'user_id' => 6,
            'job_id' => 2, // UI/UX Designer
        ]);

        FavoriteJob::create([
            'user_id' => 6,
            'job_id' => 5, // Graphic Designer
        ]);

        // Ahmad Hidayat (user_id: 7) favorites
        FavoriteJob::create([
            'user_id' => 7,
            'job_id' => 4, // Digital Marketing Specialist
        ]);

        FavoriteJob::create([
            'user_id' => 7,
            'job_id' => 6, // Content Writer
        ]);

        // Dewi Lestari (user_id: 8) favorites
        FavoriteJob::create([
            'user_id' => 8,
            'job_id' => 10, // HRD Staff
        ]);

        FavoriteJob::create([
            'user_id' => 8,
            'job_id' => 1, // Full Stack Developer
        ]);
    }
}
