<?php

namespace App\Actions\Fortify;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'required_without:phone',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'phone' => [
                'nullable',
                'required_without:email',
                'string',
                'regex:/^(\+91)?[6-9]\d{9}$/',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ], [
            'email.required_without' => 'Please provide either an email address or mobile number.',
            'phone.required_without' => 'Please provide either an email address or mobile number.',
            'phone.regex' => 'Please enter a valid Indian mobile number (e.g. +919876543210 or 9876543210).',
        ])->validate();

        $phone = $input['phone'] ?? null;
        if ($phone && ! str_starts_with($phone, '+91')) {
            $phone = '+91'.ltrim($phone, '0');
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'] ?? null,
            'phone' => $phone,
            'password' => Hash::make($input['password']),
            'role' => UserRole::Customer,
        ]);
    }
}
