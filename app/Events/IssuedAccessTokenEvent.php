<?php

declare(strict_types=1);

namespace Yulo\Events;

use Yulo\Data\IssueAccessTokenDto;
use Yulo\Data\IssuedAccessTokenDto;
use Illuminate\Foundation\Events\Dispatchable;

class IssuedAccessTokenEvent
{
    use Dispatchable;

    public function __construct(
        public IssueAccessTokenDto $issueRequestDto,
        public IssuedAccessTokenDto $issuedAccessTokenDto,
    ) {
    }
}
