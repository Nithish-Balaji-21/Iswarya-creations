<?php

namespace App\Services\Otp;

use App\Enums\OtpPurpose;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailOtpSender implements OtpSenderInterface
{
    public function send(string $identifier, string $plainOtp, OtpPurpose $purpose): bool
    {
        // If it's an email address, dispatch the branded OTP email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($identifier)->send(new OtpMail($plainOtp, $purpose));

                return true;
            } catch (Throwable $e) {
                Log::error("Failed to send OTP email to {$identifier}: ".$e->getMessage());

                return false;
            }
        }

        // For phone numbers when SMS gateway is not yet attached, log for development & mock
        Log::info("OTP dispatch for [{$identifier}] purpose [{$purpose->value}]: {$plainOtp}");

        return true;
    }
}
