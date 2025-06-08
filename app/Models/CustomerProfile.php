<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CustomerProfile extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'omang_number',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'postal_code',
        'city',
        'district',
        'occupation',
        'employer',
        'income_range',
        'verification_status',
        'rejection_reason',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(VerificationDocument::class);
    }

    public function verificationSessions()
    {
        return $this->hasMany(VerificationSession::class);
    }

    public function omangVerificationLogs()
    {
        return $this->hasMany(OmangVerificationLog::class);
    }

    public function reviews()
    {
        return $this->hasMany(BankOfficerReview::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getOmangDocument($type)
    {
        return $this->documents()->where('document_type', $type)->latest()->first();
    }

    public function getLatestSelfie()
    {
        return $this->documents()->where('document_type', 'selfie')->latest()->first();
    }

    public function getLatestVerificationSession()
    {
        return $this->verificationSessions()->latest()->first();
    }

    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }

    public function isPending()
    {
        return $this->verification_status === 'pending';
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['verification_status', 'rejection_reason'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
