<?php

namespace Tests\Feature;

use App\Enums\OtpPurpose;
use App\Enums\UserRole;
use App\Models\OtpCode;
use App\Services\Otp\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_otp_code_is_generated_and_never_stored_in_raw_form(): void
    {
        $otpService = app(OtpService::class);
        $identifier = '+919876543210';

        $plainOtp = $otpService->generate($identifier, OtpPurpose::Login);

        $this->assertNotNull($plainOtp);
        $this->assertEquals(6, strlen($plainOtp));
        $this->assertTrue(ctype_digit($plainOtp));

        $record = OtpCode::where('identifier', $identifier)
            ->where('purpose', OtpPurpose::Login)
            ->first();

        $this->assertNotNull($record);
        // Verify raw code is NOT stored directly
        $this->assertNotEquals($plainOtp, $record->otp_hash);
        // Verify hash verifies correctly
        $this->assertTrue(Hash::check($plainOtp, $record->otp_hash));
    }

    public function test_valid_otp_verifies_and_logs_in_customer(): void
    {
        $otpService = app(OtpService::class);
        $identifier = 'patron@example.com';
        $plainOtp = $otpService->generate($identifier, OtpPurpose::Login);

        $response = $this->post(route('auth.otp.verify.submit'), [
            'identifier' => $identifier,
            'purpose' => 'login',
            'otp' => $plainOtp,
        ]);

        $response->assertRedirect(route('account.dashboard'));

        // Customer account auto-provisioned
        $this->assertDatabaseHas('users', [
            'email' => $identifier,
            'role' => UserRole::Customer->value,
        ]);

        $this->assertAuthenticated('web');
    }

    public function test_invalid_otp_fails_and_increments_attempts(): void
    {
        $otpService = app(OtpService::class);
        $identifier = '+919988776655';
        $plainOtp = $otpService->generate($identifier, OtpPurpose::Login);

        $response = $this->from(route('auth.otp.verify'))->post(route('auth.otp.verify.submit'), [
            'identifier' => $identifier,
            'purpose' => 'login',
            'otp' => '000000', // incorrect
        ]);

        $response->assertRedirect(route('auth.otp.verify'));
        $response->assertSessionHasErrors('otp');

        $record = OtpCode::where('identifier', $identifier)->first();
        $this->assertEquals(1, $record->attempts);
        $this->assertNull($record->verified_at);
        $this->assertGuest('web');
    }
}
