<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VerificationDocument extends Model
{
    use HasFactory;

    /**
     * Status constants to prevent errors
     */
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';
    
    /**
     * Document type constants
     */
    const TYPE_OMANG_FRONT = 'omang_front';
    const TYPE_OMANG_BACK = 'omang_back';
    const TYPE_SELFIE = 'selfie';
    const TYPE_PROOF_OF_ADDRESS = 'proof_of_address';
    const TYPE_OTHER = 'other';

    protected $fillable = [
        'customer_profile_id',
        'document_type',
        'file_path',
        'mime_type',
        'file_size',
        'original_filename',
        'uploaded_at',
        'verification_status',
        'rejection_reason',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function getUrl()
    {
        return Storage::url($this->file_path);
    }

    public function delete()
    {
        Storage::delete($this->file_path);
        return parent::delete();
    }

    public function isImage()
    {
        return strpos($this->mime_type, 'image/') === 0;
    }

    public function isPdf()
    {
        return $this->mime_type === 'application/pdf';
    }

    public function isVerified()
    {
        return $this->verification_status === self::STATUS_VERIFIED;
    }

    public function isRejected()
    {
        return $this->verification_status === self::STATUS_REJECTED;
    }

    public function isPending()
    {
        return $this->verification_status === self::STATUS_PENDING;
    }
}