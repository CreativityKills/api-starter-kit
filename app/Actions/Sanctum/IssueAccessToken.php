<?php

declare(strict_types=1);

namespace App\Actions\Sanctum;

use App\Data\IssueAccessTokenDto;
use App\Data\IssuedAccessTokenDto;
use App\Events\IssuedAccessTokenEvent;
use App\Events\IssuingAccessTokenEvent;

readonly class IssueAccessToken
{
    public function handle(IssueAccessTokenDto $issueRequestDto): IssuedAccessTokenDto
    {
        IssuingAccessTokenEvent::dispatch($issueRequestDto);

        return tap(
            $issueRequestDto->user->createTokenFromDto($issueRequestDto),
            fn(IssuedAccessTokenDto $token) => IssuedAccessTokenEvent::dispatch($issueRequestDto, $token)
        );
    }
}
