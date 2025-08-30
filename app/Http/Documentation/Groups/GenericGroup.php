<?php

declare(strict_types=1);

namespace Yulo\Http\Documentation\Groups;

use Attribute;
use Knuckles\Scribe\Attributes\Group;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class GenericGroup extends Group
{
    public function __construct(string $name, ?string $description = '', ?bool $authenticated = null)
    {
        parent::__construct($name, $description, $authenticated);
    }
}
