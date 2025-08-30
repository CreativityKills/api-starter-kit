<?php

declare(strict_types=1);

namespace Yulo\Data;

use Carbon\Carbon;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

readonly class IssuedAccessTokenDto
{
    public function __construct(
        public NewAccessToken $accessToken,
        public NewAccessToken $refreshToken,
    ) {
    }

    public function getAccessToken(): PersonalAccessToken
    {
        return $this->accessToken->accessToken;
    }

    public function getPlainTextAccessToken(): string
    {
        return $this->accessToken->plainTextToken;
    }

    public function getAccessTokenExpiration(): ?Carbon
    {
        return $this->getAccessToken()->expires_at;
    }

    public function getRefreshToken(): PersonalAccessToken
    {
        return $this->refreshToken->accessToken;
    }

    public function getPlainTextRefreshToken(): string
    {
        return $this->refreshToken->plainTextToken;
    }

    public function getRefreshTokenExpiration(): ?Carbon
    {
        return $this->getRefreshToken()->expires_at;
    }
}
