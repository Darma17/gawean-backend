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
        Schema::table('applied_jobs', function (Blueprint $table) {
            $table->dateTime('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('interview_link')->nullable();
            $table->text('interview_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applied_jobs', function (Blueprint $table) {
            $table->dropColumn(['interview_date', 'interview_time', 'interview_link', 'interview_message']);
        });
    }
};
