<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationSession extends Model
{
    use HasFactory;

    /**
     * Status constants for verification sessions
     * These match the ENUM values in your database
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';  // This matches your database ENUM
    const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_profile_id',
        'omang_photo_path',
        'selfie_photo_path',
        'similarity_score',
        'ip_address',
        'user_agent',
        'device_details',
        'location_details',
        'status',
        'reviewed_by',
        'reviewed_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'similarity_score' => 'float',
        'device_details' => 'array',
        'location_details' => 'array',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer profile associated with this session.
     */
    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    /**
     * Get the user who reviewed this session.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if this session is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if this session is rejected.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if this session is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Format the similarity score as a percentage.
     *
     * @return string
     */
    public function getSimilarityPercentage()
    {
        return number_format($this->similarity_score, 1) . '%';
    }

    /**
     * Get the match details from the location_details JSON.
     *
     * @return array|null
     */
    public function getMatchDetails()
    {
        if (isset($this->location_details['match_details'])) {
            return $this->location_details['match_details'];
        }
        
        return null;
    }
}