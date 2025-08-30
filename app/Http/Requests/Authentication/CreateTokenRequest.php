<?php

declare(strict_types=1);

namespace Yulo\Http\Requests\Authentication;

use Yulo\Contracts\SupportsDocumentation;
use Laravel\Fortify\Http\Requests\LoginRequest;

class CreateTokenRequest extends LoginRequest implements SupportsDocumentation
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'device' => ['string', 'between:1,100'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'The email address of the user.',
                'example' => 'john.doe@yulo.app',
            ],
            'password' => [
                'description' => 'The password of the user.',
                'example' => 'password',
            ],
            'device' => [
                'description' => 'The name of the device.',
                'example' => 'iPhone',
            ],
        ];
    }
}
