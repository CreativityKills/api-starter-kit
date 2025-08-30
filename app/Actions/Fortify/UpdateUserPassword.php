<?php

declare(strict_types=1);

namespace Yulo\Actions\Fortify;

use Yulo\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yulo\Concerns\PasswordValidationRules;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
            'current_password' => ['required', 'string', 'current_password:web'],
        ], [
            'current_password.current_password' => __('fortify.invalid-password-confirmation'),
        ])->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
