<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jobs from PT Teknologi Indonesia (user_id: 2)
        Job::create([
            'user_id' => 2,
            'title' => 'Full Stack Developer',
            'lokasi_kerja' => 'Jakarta Selatan',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 3,
            'bidang' => 'Information Technology',
            'skill_yang_dibutuhkan' => 'PHP, Laravel, JavaScript, Vue.js/React, MySQL, Git, RESTful API',
            'gaji' => 'Rp 10.000.000 - Rp 15.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '09:00 - 18:00',
            'deskripsi' => 'Kami mencari Full Stack Developer yang berpengalaman untuk bergabung dengan tim development kami. Kandidat akan bertanggung jawab dalam pengembangan aplikasi web dari frontend hingga backend.',
        ]);

        Job::create([
            'user_id' => 2,
            'title' => 'UI/UX Designer',
            'lokasi_kerja' => 'Jakarta Selatan',
            'tipe' => 'remote',
            'jumlah_lowongan' => 2,
            'bidang' => 'Design',
            'skill_yang_dibutuhkan' => 'Figma, Adobe XD, Sketch, User Research, Wireframing, Prototyping',
            'gaji' => 'Rp 8.000.000 - Rp 12.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => 'Flexible',
            'deskripsi' => 'Kami mencari UI/UX Designer kreatif untuk merancang pengalaman pengguna yang luar biasa untuk produk-produk digital kami.',
        ]);

        Job::create([
            'user_id' => 2,
            'title' => 'DevOps Engineer',
            'lokasi_kerja' => 'Jakarta Selatan',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 1,
            'bidang' => 'Information Technology',
            'skill_yang_dibutuhkan' => 'Docker, Kubernetes, AWS/GCP, CI/CD, Linux, Terraform, Monitoring',
            'gaji' => 'Rp 15.000.000 - Rp 25.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '09:00 - 18:00',
            'deskripsi' => 'Bertanggung jawab dalam mengelola infrastruktur cloud, automation, dan deployment pipeline.',
        ]);

        // Jobs from CV Digital Kreasi (user_id: 3)
        Job::create([
            'user_id' => 3,
            'title' => 'Digital Marketing Specialist',
            'lokasi_kerja' => 'Jakarta Pusat',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 2,
            'bidang' => 'Marketing',
            'skill_yang_dibutuhkan' => 'Google Ads, Facebook Ads, SEO, SEM, Google Analytics, Content Marketing',
            'gaji' => 'Rp 6.000.000 - Rp 10.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Mengelola kampanye digital marketing untuk berbagai klien, menganalisis performa, dan mengoptimalkan ROI.',
        ]);

        Job::create([
            'user_id' => 3,
            'title' => 'Graphic Designer',
            'lokasi_kerja' => 'Jakarta Pusat',
            'tipe' => 'remote',
            'jumlah_lowongan' => 3,
            'bidang' => 'Design',
            'skill_yang_dibutuhkan' => 'Adobe Photoshop, Adobe Illustrator, CorelDraw, Canva, Video Editing',
            'gaji' => 'Rp 5.000.000 - Rp 8.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => 'Flexible',
            'deskripsi' => 'Membuat desain visual untuk kebutuhan marketing digital, termasuk banner, social media content, dan materi promosi.',
        ]);

        Job::create([
            'user_id' => 3,
            'title' => 'Content Writer',
            'lokasi_kerja' => 'Jakarta Pusat',
            'tipe' => 'remote',
            'jumlah_lowongan' => 2,
            'bidang' => 'Content & Media',
            'skill_yang_dibutuhkan' => 'Copywriting, SEO Writing, Research Skills, Social Media Management',
            'gaji' => 'Rp 4.000.000 - Rp 7.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => 'Flexible',
            'deskripsi' => 'Menulis konten berkualitas untuk website, blog, dan social media klien.',
        ]);

        // Jobs from PT Maju Bersama (user_id: 4)
        Job::create([
            'user_id' => 4,
            'title' => 'Production Supervisor',
            'lokasi_kerja' => 'Bandung',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 1,
            'bidang' => 'Manufacturing',
            'skill_yang_dibutuhkan' => 'Production Planning, Quality Control, Team Management, Lean Manufacturing',
            'gaji' => 'Rp 8.000.000 - Rp 12.000.000',
            'jadwal_kerja' => 'Senin - Sabtu',
            'jam_kerja' => '07:00 - 16:00',
            'deskripsi' => 'Mengawasi proses produksi harian, memastikan target tercapai, dan menjaga standar kualitas.',
        ]);

        Job::create([
            'user_id' => 4,
            'title' => 'Warehouse Staff',
            'lokasi_kerja' => 'Bandung',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 5,
            'bidang' => 'Logistics',
            'skill_yang_dibutuhkan' => 'Inventory Management, Forklift Operation, Ms. Excel, Physical Fitness',
            'gaji' => 'Rp 3.500.000 - Rp 5.000.000',
            'jadwal_kerja' => 'Senin - Sabtu',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Mengelola keluar masuk barang di gudang, melakukan stock opname, dan memastikan kerapian penyimpanan.',
        ]);

        Job::create([
            'user_id' => 4,
            'title' => 'Quality Control Staff',
            'lokasi_kerja' => 'Bandung',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 2,
            'bidang' => 'Quality Assurance',
            'skill_yang_dibutuhkan' => 'Quality Management System, ISO Standards, Statistical Analysis, Attention to Detail',
            'gaji' => 'Rp 4.500.000 - Rp 6.500.000',
            'jadwal_kerja' => 'Senin - Sabtu',
            'jam_kerja' => '07:00 - 16:00',
            'deskripsi' => 'Melakukan pengujian dan inspeksi produk untuk memastikan kualitas sesuai standar perusahaan.',
        ]);

        Job::create([
            'user_id' => 4,
            'title' => 'HRD Staff',
            'lokasi_kerja' => 'Bandung',
            'tipe' => 'onsite',
            'jumlah_lowongan' => 1,
            'bidang' => 'Human Resources',
            'skill_yang_dibutuhkan' => 'Recruitment, Payroll, Employee Relations, Ms. Office, HRIS',
            'gaji' => 'Rp 5.000.000 - Rp 7.000.000',
            'jadwal_kerja' => 'Senin - Jumat',
            'jam_kerja' => '08:00 - 17:00',
            'deskripsi' => 'Menangani rekrutmen, administrasi kepegawaian, dan hubungan industrial.',
        ]);
    }
}
