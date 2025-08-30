<?php

declare(strict_types=1);

namespace App\Data\Enums;

use Illuminate\Support\Arr;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;

enum AccessLevel: string
{
    case FULL = 'FULL';
    case BASIC = 'BASIC';
    case TRIAL = 'TRIAL';

    /**
     * @return ($asString is true ? string : string[])
     */
    public function toAbilities(bool $asString = false): array|string
    {
        return AccessLevelAbilities::for($this, $asString);
    }

    public static function canRefreshAccessTokenMiddleware(): string
    {
        return self::middleware(AccessLevelAbilities::SYSTEM__REFRESH_ACCESS_TOKEN);
    }

    /**
     * @param  AccessLevelAbilities|AccessLevelAbilities[]  $abilities
     */
    private static function middleware(AccessLevelAbilities|array $abilities): string
    {
        $abilities = Arr::wrap($abilities);
        $abilitiesString = implode(',', array_map(fn(AccessLevelAbilities $ability) => $ability->value, $abilities));

        return CheckAbilities::class.':'.$abilitiesString;
    }
}
