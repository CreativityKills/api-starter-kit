<?php

declare(strict_types=1);

namespace Yulo\Events;

use Yulo\Data\IssueAccessTokenDto;
use Illuminate\Foundation\Events\Dispatchable;

class IssuingAccessTokenEvent
{
    use Dispatchable;

    public function __construct(
        public IssueAccessTokenDto $issueRequestDto
    ) {
    }
}
