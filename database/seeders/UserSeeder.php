<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'nama' => 'Admin Gawean',
            'email' => 'admin@gawean.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '081234567890',
            'alamat' => 'Jl. Admin No. 1, Jakarta',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Company Users
        User::create([
            'nama' => 'PT Teknologi Indonesia',
            'email' => 'hrd@teknologi-indonesia.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '021234567891',
            'alamat' => 'Jl. Sudirman No. 100, Jakarta Selatan',
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'CV Digital Kreasi',
            'email' => 'recruitment@digitalkreasi.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '021234567892',
            'alamat' => 'Jl. Gatot Subroto No. 50, Jakarta',
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'PT Maju Bersama',
            'email' => 'hr@majubersama.co.id',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '022345678901',
            'alamat' => 'Jl. Asia Afrika No. 25, Bandung',
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        // Regular Users
        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '081234567001',
            'alamat' => 'Jl. Melati No. 10, Surabaya',
            'role' => 'user',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'Siti Rahayu',
            'email' => 'siti.rahayu@gmail.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '081234567002',
            'alamat' => 'Jl. Mawar No. 15, Yogyakarta',
            'role' => 'user',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'Ahmad Hidayat',
            'email' => 'ahmad.hidayat@gmail.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '081234567003',
            'alamat' => 'Jl. Kenanga No. 20, Semarang',
            'role' => 'user',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'Dewi Lestari',
            'email' => 'dewi.lestari@gmail.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '081234567004',
            'alamat' => 'Jl. Anggrek No. 5, Malang',
            'role' => 'user',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'Rudi Hermawan',
            'email' => 'rudi.hermawan@gmail.com',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '081234567005',
            'alamat' => 'Jl. Dahlia No. 8, Medan',
            'role' => 'user',
            'is_active' => false,
        ]);
    }
}
