<?php

declare(strict_types=1);

namespace Yulo\Actions\Sanctum;

use Yulo\Data\IssueAccessTokenDto;
use Yulo\Data\IssuedAccessTokenDto;
use Yulo\Events\IssuedAccessTokenEvent;
use Yulo\Events\IssuingAccessTokenEvent;

readonly class IssueAccessToken
{
    public function handle(IssueAccessTokenDto $issueRequestDto): IssuedAccessTokenDto
    {
        IssuingAccessTokenEvent::dispatch($issueRequestDto);

        return tap(
            $issueRequestDto->user->createTokenFromDto($issueRequestDto),
            fn (IssuedAccessTokenDto $token) => IssuedAccessTokenEvent::dispatch($issueRequestDto, $token)
        );
    }
}
