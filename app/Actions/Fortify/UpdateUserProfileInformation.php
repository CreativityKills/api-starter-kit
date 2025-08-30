<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'between:2,255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ])->validateWithBag('updateProfileInformation');

        $this->updateUser($user, $input);
    }

    /**
     * @param  array<string, string>  $input
     */
    private function updateUser(User $user, array $input): void
    {
        $attributes = Arr::only($input, ['name', 'email']);
        $shouldSendEmailVerifyNotification = $user->email !== $attributes['email'] && $user instanceof MustVerifyEmail;

        if ($shouldSendEmailVerifyNotification) {
            $attributes['email_verified_at'] = null;
        }

        $user->forceFill($attributes)->save();

        if ($shouldSendEmailVerifyNotification) {
            $user->sendEmailVerificationNotification();
        }
    }
}
