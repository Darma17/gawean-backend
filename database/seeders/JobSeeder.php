<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil perusahaan berdasarkan email (pasti unik & stabil)
        $pertamina = User::where('email', 'darma1748darma@gmail.com')->first();
        $pln       = User::where('email', 'recruitment@pln.co.id')->first();
        $idstar    = User::where('email', 'hr@idstar.co.id')->first();
        $bca       = User::where('email', 'recruitment@bca.co.id')->first();
        $telkomsel = User::where('email', 'recruitment@telkomsel.co.id')->first();

        // =========================
        // Jobs - PT Pertamina
        // =========================
        Job::create([
            'user_id' => $pertamina->id,
            'title' => 'Field Engineer Migas',
            'lokasi_kerja' => 'Balikpapan',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 5,
            'bidang' => 'Energy',
            'skill_yang_dibutuhkan' => 'Oil & Gas Operations, Safety Procedure, Mechanical Engineering',
            'gaji' => 'Rp 12.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Bertanggung jawab atas operasional lapangan migas dan keselamatan kerja.',
        ]);

        Job::create([
            'user_id' => $pertamina->id,
            'title' => 'HSE Officer',
            'lokasi_kerja' => 'Jakarta Pusat',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 2,
            'bidang' => 'Safety & Compliance',
            'skill_yang_dibutuhkan' => 'HSE Management, ISO 45001, Risk Assessment',
            'gaji' => 'Rp 10.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Mengawasi penerapan standar keselamatan dan kesehatan kerja.',
        ]);

        // =========================
        // Jobs - PT PLN
        // =========================
        Job::create([
            'user_id' => $pln->id,
            'title' => 'Electrical Engineer',
            'lokasi_kerja' => 'Jakarta Timur',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 3,
            'bidang' => 'Energy',
            'skill_yang_dibutuhkan' => 'Power System, AutoCAD, Electrical Maintenance',
            'gaji' => 'Rp 9.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Mengelola dan memelihara sistem kelistrikan distribusi.',
        ]);

        Job::create([
            'user_id' => $pln->id,
            'title' => 'Technician Gardu Induk',
            'lokasi_kerja' => 'Bekasi',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 4,
            'bidang' => 'Maintenance',
            'skill_yang_dibutuhkan' => 'High Voltage System, Electrical Safety, Troubleshooting',
            'gaji' => 'Rp 7.500.000',
            'jadwal_kerja' => 'Shift',
            'jam_kerja' => 'Shift System',
            'deskripsi' => 'Melakukan perawatan dan perbaikan gardu induk.',
        ]);

        // =========================
        // Jobs - PT IDStar
        // =========================
        Job::create([
            'user_id' => $idstar->id,
            'title' => 'Backend Developer',
            'lokasi_kerja' => 'Jakarta Selatan',
            'tipe' => 'remote',
            'jumlah_lowongan' => 4,
            'bidang' => 'Information Technology',
            'skill_yang_dibutuhkan' => 'Java, Spring Boot, REST API, PostgreSQL, Git',
            'gaji' => 'Rp 11.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => 'Flexible',
            'deskripsi' => 'Mengembangkan dan memelihara sistem backend perusahaan.',
        ]);

        Job::create([
            'user_id' => $idstar->id,
            'title' => 'QA Engineer',
            'lokasi_kerja' => 'Jakarta Selatan',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 2,
            'bidang' => 'Quality Assurance',
            'skill_yang_dibutuhkan' => 'Manual Testing, Automation Testing, Selenium, Test Case',
            'gaji' => 'Rp 9.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '09:00 - 18:00',
            'deskripsi' => 'Melakukan pengujian aplikasi sebelum rilis.',
        ]);

        // =========================
        // Jobs - PT BCA
        // =========================
        Job::create([
            'user_id' => $bca->id,
            'title' => 'IT Support Officer',
            'lokasi_kerja' => 'Jakarta Pusat',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 2,
            'bidang' => 'Banking & IT',
            'skill_yang_dibutuhkan' => 'Windows Server, Networking, Helpdesk, Troubleshooting',
            'gaji' => 'Rp 8.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Memberikan dukungan teknis sistem perbankan.',
        ]);

        Job::create([
            'user_id' => $bca->id,
            'title' => 'Data Analyst Junior',
            'lokasi_kerja' => 'Jakarta Pusat',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 3,
            'bidang' => 'Data & Analytics',
            'skill_yang_dibutuhkan' => 'SQL, Excel, Power BI, Statistik Dasar',
            'gaji' => 'Rp 9.500.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Menganalisis data operasional dan bisnis.',
        ]);

        // =========================
        // Jobs - Telkomsel
        // =========================
        Job::create([
            'user_id' => $telkomsel->id,
            'title' => 'Network Operation Engineer',
            'lokasi_kerja' => 'Jakarta Selatan',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 3,
            'bidang' => 'Telecommunication',
            'skill_yang_dibutuhkan' => 'Networking, Linux, Monitoring System, Troubleshooting',
            'gaji' => 'Rp 10.000.000',
            'jadwal_kerja' => 'Shift',
            'jam_kerja' => 'Shift System',
            'deskripsi' => 'Mengawasi dan menjaga stabilitas jaringan.',
        ]);

        Job::create([
            'user_id' => $telkomsel->id,
            'title' => 'Radio Access Network Engineer',
            'lokasi_kerja' => 'Depok',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 2,
            'bidang' => 'Telecommunication',
            'skill_yang_dibutuhkan' => 'RAN, LTE/5G, RF Planning, Drive Test',
            'gaji' => 'Rp 11.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Mengelola dan optimasi jaringan radio.',
        ]);
    }
}
