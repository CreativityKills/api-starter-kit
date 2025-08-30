<?php

declare(strict_types=1);

namespace App\Events;

use App\Data\IssueAccessTokenDto;
use App\Data\IssuedAccessTokenDto;
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
