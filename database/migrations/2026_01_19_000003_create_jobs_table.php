<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs_listing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('lokasi_kerja');
            $table->enum('tipe', ['onsite', 'remote'])->default('onsite');
            $table->integer('jumlah_lowongan')->default(1);
            $table->string('bidang');
            $table->text('skill_yang_dibutuhkan');
            $table->string('gaji')->nullable();
            $table->string('jadwal_kerja')->nullable();
            $table->string('jam_kerja')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs_listing');
    }
};
