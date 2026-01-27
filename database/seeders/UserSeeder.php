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
            'nama' => 'PT Pertamina (Persero)',
            'email' => 'recruitment@pertamina.co.id',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '0213815111',
            'alamat' => 'Jl. Medan Merdeka Timur No. 1A, Jakarta Pusat',
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'PT PLN (Persero)',
            'email' => 'recruitment@pln.co.id',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '0217261122',
            'alamat' => 'Jl. Trunojoyo Blok M I No. 135, Jakarta Selatan',
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'PT IDStar Cipta Teknologi',
            'email' => 'hr@idstar.co.id',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '02129012345',
            'alamat' => 'Jl. TB Simatupang No. 18, Jakarta Selatan',
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'PT Bank Central Asia Tbk',
            'email' => 'recruitment@bca.co.id',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '02123588000',
            'alamat' => 'Menara BCA, Jl. MH Thamrin No. 1, Jakarta Pusat',
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        User::create([
            'nama' => 'PT Telekomunikasi Selular (Telkomsel)',
            'email' => 'recruitment@telkomsel.co.id',
            'password' => Hash::make('password123'),
            'nomor_telepon' => '0215240811',
            'alamat' => 'Jl. Jend. Gatot Subroto Kav. 52, Jakarta Selatan',
            'role' => 'perusahaan',
            'is_active' => true,
        ]);


        // Regular Users
        User::create([
            'nama' => 'Damore Velnava',
            'email' => 'bukuku.real@gmail.com',
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
