<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Profile for Budi Santoso (user_id: 5)
        UserProfile::create([
            'user_id' => 5,
            'cv' => null,
            'ijazah_terakhir' => null,
            'ktp' => null,
            'portofolio' => null,
        ]);

        // Profile for Siti Rahayu (user_id: 6)
        UserProfile::create([
            'user_id' => 6,
            'cv' => null,
            'ijazah_terakhir' => null,
            'ktp' => null,
            'portofolio' => null,
        ]);

        // Profile for Ahmad Hidayat (user_id: 7)
        UserProfile::create([
            'user_id' => 7,
            'cv' => null,
            'ijazah_terakhir' => null,
            'ktp' => null,
            'portofolio' => null,
        ]);

        // Profile for Dewi Lestari (user_id: 8)
        UserProfile::create([
            'user_id' => 8,
            'cv' => null,
            'ijazah_terakhir' => null,
            'ktp' => null,
            'portofolio' => null,
        ]);

        // Profile for Rudi Hermawan (user_id: 9)
        UserProfile::create([
            'user_id' => 9,
            'cv' => null,
            'ijazah_terakhir' => null,
            'ktp' => null,
            'portofolio' => null,
        ]);
    }
}
