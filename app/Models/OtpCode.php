<?php

namespace App\Models;

use App\Enums\OtpPurpose;
use Database\Factories\OtpCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class OtpCode extends Model
{
    /** @use HasFactory<OtpCodeFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'identifier',
        'purpose',
        'otp_hash',
        'attempts',
        'expires_at',
        'verified_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'purpose' => OtpPurpose::class,
            'attempts' => 'integer',
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function verify(string $plainOtp, int $maxAttempts = 5): bool
    {
        if ($this->isExpired() || $this->isVerified() || $this->attempts >= $maxAttempts) {
            return false;
        }

        if (Hash::check($plainOtp, $this->otp_hash)) {
            $this->update(['verified_at' => now()]);

            return true;
        }

        $this->increment('attempts');

        return false;
    }
}
