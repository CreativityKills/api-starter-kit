<?php

declare(strict_types=1);

namespace Yulo\Http\Requests\Authentication;

use Throwable;
use Laravel\Sanctum\PersonalAccessToken;
use Yulo\Contracts\SupportsDocumentation;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest implements SupportsDocumentation
{
    protected ?PersonalAccessToken $userAccessToken = null;

    public function authorize(): bool
    {
        try {
            $this->getUserAccessToken();
        } catch (Throwable) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'device' => ['string', 'between:1,100'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'token' => [
                'description' => 'The refresh token to be used to refresh the access token.',
                'example' => '32|yulo.kKZv3U9MrlGHaUXwM7pXW232sHcxRo12Kb3VfuEYf3fab36b',
            ],
            'device' => [
                'description' => 'The name of the device.',
                'example' => 'iPhone',
            ],
        ];
    }

    public function getUserAccessToken(): PersonalAccessToken
    {
        if (! $this->userAccessToken) {
            $hashedToken = hash('sha256', $this->string('token')->explode('|')->last(default: ''));
            $this->userAccessToken = currentUserOrFail()->tokens()->where('token', $hashedToken)->firstOrFail();
        }

        return $this->userAccessToken;
    }

    public function getUserRefreshToken(): PersonalAccessToken
    {
        return currentUserOrFail()->currentAccessToken();
    }
}
