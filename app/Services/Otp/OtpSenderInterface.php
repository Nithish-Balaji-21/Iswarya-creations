<?php

namespace App\Services\Otp;

use App\Enums\OtpPurpose;

interface OtpSenderInterface
{
    /**
     * Send OTP to recipient identifier (email or phone).
     */
    public function send(string $identifier, string $plainOtp, OtpPurpose $purpose): bool;
}
