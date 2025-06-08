<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OmangVerificationLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_profile_id',
        'omang_number',
        'request_payload',
        'response_payload',
        'is_successful',
        'error_message',
        'verification_timestamp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'request_payload' => 'json',
        'response_payload' => 'json',
        'is_successful' => 'boolean',
        'verification_timestamp' => 'datetime',
    ];

    /**
     * Get the customer profile associated with the verification log.
     */
    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }
}