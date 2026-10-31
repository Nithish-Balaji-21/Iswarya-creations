<?php

namespace App\Services\Otp;

use App\Enums\OtpPurpose;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    public function __construct(
        protected OtpSenderInterface $sender
    ) {}

    /**
     * Generate and dispatch a 6-digit OTP code.
     */
    public function generate(string $identifier, OtpPurpose $purpose): ?string
    {
        // Rate limit: 3 OTP requests per 5 minutes per identifier+purpose
        $rateKey = 'otp-generate:'.md5($identifier.'|'.$purpose->value);
        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            return null;
        }
        RateLimiter::hit($rateKey, 300);

        // Generate 6-digit cryptographic random number
        $plainOtp = (string) random_int(100000, 999999);
        $expiryMinutes = (int) config('iswarya.otp_expiry_minutes', 10);

        OtpCode::create([
            'identifier' => $identifier,
            'purpose' => $purpose,
            'otp_hash' => Hash::make($plainOtp),
            'attempts' => 0,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'verified_at' => null,
            'created_at' => now(),
        ]);

        $this->sender->send($identifier, $plainOtp, $purpose);

        return $plainOtp;
    }

    /**
     * Verify an OTP code.
     */
    public function verify(string $identifier, OtpPurpose $purpose, string $plainOtp): bool
    {
        $maxAttempts = (int) config('iswarya.max_otp_attempts', 5);

        $otpRecord = OtpCode::where('identifier', $identifier)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        if (! $otpRecord) {
            return false;
        }

        return $otpRecord->verify($plainOtp, $maxAttempts);
    }
}
