<?php

declare(strict_types=1);

namespace Yulo\Http\Documentation\Groups;

use Attribute;
use Knuckles\Scribe\Attributes\Group;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class AuthenticationGroup extends Group
{
    public function __construct()
    {
        parent::__construct(
            name: 'Authentication',
            description: 'Endpoints for authentication related tasks',
            authenticated: false,
        );
    }
}
