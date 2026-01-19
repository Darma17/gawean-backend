<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Profile for PT Teknologi Indonesia (user_id: 2)
        CompanyProfile::create([
            'user_id' => 2,
            'tahun_berdiri' => 2010,
            'bidang_usaha' => 'Information Technology',
            'latar_belakang' => 'PT Teknologi Indonesia adalah perusahaan teknologi yang berfokus pada pengembangan solusi digital untuk berbagai industri. Didirikan oleh sekelompok profesional IT yang berpengalaman.',
            'visi' => 'Menjadi perusahaan teknologi terdepan di Indonesia yang memberikan solusi inovatif untuk meningkatkan efisiensi bisnis.',
            'misi' => 'Mengembangkan produk teknologi berkualitas tinggi, Memberikan layanan terbaik kepada klien, Memberdayakan talenta lokal di bidang teknologi.',
        ]);

        // Profile for CV Digital Kreasi (user_id: 3)
        CompanyProfile::create([
            'user_id' => 3,
            'tahun_berdiri' => 2015,
            'bidang_usaha' => 'Digital Marketing & Creative Agency',
            'latar_belakang' => 'CV Digital Kreasi adalah agensi kreatif yang menyediakan layanan digital marketing, desain grafis, dan pengembangan konten digital.',
            'visi' => 'Menjadi partner kreatif terpercaya bagi bisnis di era digital.',
            'misi' => 'Membantu bisnis berkembang melalui strategi digital yang efektif dan kreatif.',
        ]);

        // Profile for PT Maju Bersama (user_id: 4)
        CompanyProfile::create([
            'user_id' => 4,
            'tahun_berdiri' => 2005,
            'bidang_usaha' => 'Manufacturing & Distribution',
            'latar_belakang' => 'PT Maju Bersama adalah perusahaan manufaktur dan distribusi yang telah melayani pasar Indonesia selama lebih dari 15 tahun.',
            'visi' => 'Menjadi perusahaan manufaktur terkemuka dengan standar kualitas internasional.',
            'misi' => 'Memproduksi barang berkualitas dengan harga terjangkau, Membangun jaringan distribusi yang luas dan efisien.',
        ]);
    }
}
