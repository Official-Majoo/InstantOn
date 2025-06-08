<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankOfficerReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_profile_id',
        'officer_id',
        'status',
        'notes',
        'review_timestamp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'review_timestamp' => 'datetime',
    ];

    /**
     * Get the customer profile associated with the review.
     */
    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    /**
     * Get the officer who performed the review.
     */
    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}