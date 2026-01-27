<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs_listing';

    protected $fillable = [
        'user_id',
        'title',
        'lokasi_kerja',
        'tipe',
        'jumlah_lowongan',
        'bidang',
        'skill_yang_dibutuhkan',
        'gaji',
        'jadwal_kerja',
        'jam_kerja',
        'deskripsi',
    ];

    /**
     * Get the user (company) that owns the job.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company profile through user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the company profile through user.
     */
    public function companyProfile()
    {
        return $this->hasOneThrough(
            CompanyProfile::class,
            User::class,
            'id',        // Foreign key on users table
            'user_id',   // Foreign key on company_profiles table
            'user_id',   // Local key on jobs_listing table
            'id'         // Local key on users table
        );
    }

    /**
     * Get the favorite jobs for this job.
     */
    public function favoriteJobs(): HasMany
    {
        return $this->hasMany(FavoriteJob::class);
    }

    /**
     * Get the applied jobs for this job.
     */
    public function appliedJobs(): HasMany
    {
        return $this->hasMany(AppliedJob::class);
    }
}
