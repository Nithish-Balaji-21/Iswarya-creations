<?php

namespace App\Enums;

enum OtpPurpose: string
{
    case Login = 'login';
    case Register = 'register';
    case ForgotPassword = 'forgot_password';

    public function label(): string
    {
        return match ($this) {
            self::Login => 'Login Verification',
            self::Register => 'Account Registration',
            self::ForgotPassword => 'Password Reset',
        };
    }
}
