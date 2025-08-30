<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Config;

use App\Extensions\Scribe\Extracting\Strategies;
use Knuckles\Scribe\Config\Defaults as BaseDefaults;

class Defaults extends BaseDefaults
{
    /**
     * @const array<class-string<\Knuckles\Scribe\Extracting\Strategies\Strategy>>
     */
    public const array METADATA_STRATEGIES = [
        Strategies\Metadata\GetFromDocBlocks::class,
        Strategies\Metadata\GetFromMetadataAttributes::class,
    ];

    /**
     * @const array<class-string<\Knuckles\Scribe\Extracting\Strategies\Strategy>>
     */
    public const array BODY_PARAMETERS_STRATEGIES = [
        Strategies\Metadata\GetValidationRulesFromAction::class,
        ...parent::BODY_PARAMETERS_STRATEGIES,
    ];

    /**
     * @const array<class-string<\Knuckles\Scribe\Extracting\Strategies\Strategy>>
     */
    public const array RESPONSES_STRATEGIES = [
        ...parent::RESPONSES_STRATEGIES,
    ];

    /**
     * @const array<class-string<\Knuckles\Scribe\Extracting\Strategies\Strategy>>
     */
    public const array HEADERS_STRATEGIES = [
        ...parent::HEADERS_STRATEGIES,
    ];

    /**
     * @const array<class-string<\Knuckles\Scribe\Extracting\Strategies\Strategy>>
     */
    public const array URL_PARAMETERS_STRATEGIES = [
        ...parent::URL_PARAMETERS_STRATEGIES,
    ];

    /**
     * @const array<class-string<\Knuckles\Scribe\Extracting\Strategies\Strategy>>
     */
    public const array QUERY_PARAMETERS_STRATEGIES = [
        ...parent::QUERY_PARAMETERS_STRATEGIES,
    ];

    /**
     * @const array<class-string<\Knuckles\Scribe\Extracting\Strategies\Strategy>>
     */
    public const array RESPONSE_FIELDS_STRATEGIES = [
        ...parent::RESPONSE_FIELDS_STRATEGIES,
    ];
}
