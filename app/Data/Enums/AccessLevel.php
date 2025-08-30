<?php

declare(strict_types=1);

namespace App\Data\Enums;

enum AccessLevel
{
    case FULL;
    // ...

    /**
     * @return ($asString is true ? string : string[])
     */
    public function toAbilities(bool $asString = false): array|string
    {
        return Abilities::for($this, $asString);
    }
}
