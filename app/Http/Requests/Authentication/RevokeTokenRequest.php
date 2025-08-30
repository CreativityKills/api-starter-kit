<?php

declare(strict_types=1);

namespace App\Http\Requests\Authentication;

use App\Contracts\SupportsDocumentation;
use App\Data\Enums\AccessLevelAbilities;
use Illuminate\Foundation\Http\FormRequest;

class RevokeTokenRequest extends FormRequest implements SupportsDocumentation
{
    public function authorize(): bool
    {
        if ($this->revokeAll()) {
            return boolval(
                $this->user()?->tokenCan(AccessLevelAbilities::SYSTEM__REVOKE_ALL_ACCESS_TOKENS->value)
            );
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'all' => ['boolean'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'all' => [
                'description' => 'If set to true, all access & refresh tokens for the user will be revoked.',
                'example' => false,
            ],
        ];
    }

    public function revokeAll(): bool
    {
        return $this->boolean('all');
    }
}
