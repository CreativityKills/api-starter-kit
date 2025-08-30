<?php

declare(strict_types=1);

namespace App\Data\Enums;

use Illuminate\Support\Arr;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;

enum Abilities: string
{
    case SYSTEM__REFRESH_ACCESS_TOKEN = 'system:refresh-access-token';
    case SYSTEM__REVOKE_ALL_ACCESS_TOKENS = 'system:revoke-all-access-tokens';

    /**
     * @return ($asString is true ? string : string[])
     */
    public static function for(AccessLevel $level, bool $asString = false): array|string
    {
        $abilities = match ($level) {
            AccessLevel::FULL => ['*'],
        };

        return $asString ? implode(',', $abilities) : $abilities;
    }

    /**
     * @param  self|self[]  $abilities
     */
    public static function check(self|array $abilities): string
    {
        $abilities = Arr::wrap($abilities);
        $abilitiesString = implode(',', array_map(fn (Abilities $ability) => $ability->value, $abilities));

        return CheckAbilities::class.':'.$abilitiesString;
    }

    /**
     * @return self[]
     */
    public static function forRefreshToken(): array
    {
        return [
            self::SYSTEM__REFRESH_ACCESS_TOKEN,
        ];
    }
}
