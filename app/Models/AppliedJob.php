<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppliedJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'status',
        'interview_date',
        'interview_time',
        'interview_link',
        'interview_message',
    ];

    /**
     * Get the user that applied.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job that was applied to.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
