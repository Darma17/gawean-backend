<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfileCertificate extends Model
{
    protected $fillable = [
        'user_profile_id',
        'file_path',
        'file_name',
    ];

    /**
     * Get the user profile that owns the certificate.
     */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }
}
