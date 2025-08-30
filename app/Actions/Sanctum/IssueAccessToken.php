<?php

declare(strict_types=1);

namespace App\Actions\Sanctum;

use App\Data\Enums\Abilities;
use App\Data\IssueAccessTokenDto;
use App\Data\IssuedAccessTokenDto;
use App\Events\IssuedAccessTokenEvent;
use Illuminate\Support\Facades\Config;
use App\Events\IssuingAccessTokenEvent;

readonly class IssueAccessToken
{
    public function handle(IssueAccessTokenDto $issueRequestDto): IssuedAccessTokenDto
    {
        IssuingAccessTokenEvent::dispatch($issueRequestDto);

        return tap(
            $this->createTokens($issueRequestDto),
            fn (IssuedAccessTokenDto $token) => IssuedAccessTokenEvent::dispatch($issueRequestDto, $token)
        );
    }

    protected function createTokens(IssueAccessTokenDto $issueRequestDto): IssuedAccessTokenDto
    {
        $accessToken = $issueRequestDto->user->createToken(
            name: $issueRequestDto->name,
            abilities: $issueRequestDto->accessLevel->toAbilities(),
            expiresAt: $issueRequestDto->expiresAt
        );

        $refreshToken = $issueRequestDto->user->createToken(
            name: $issueRequestDto->name,
            abilities: Abilities::forRefreshToken(),
            expiresAt: now()->addHours(Config::integer('sanctum.refresh_expiration'))
        );

        return new IssuedAccessTokenDto(accessToken: $accessToken, refreshToken: $refreshToken);
    }
}
