<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Extracting\Strategies\Metadata;

use App\Extensions\Scribe\Concerns\ExtendableAttributeNames;
use Knuckles\Scribe\Extracting\Strategies\Metadata\GetFromMetadataAttributes as BaseGetFromMetadataAttributes;

class GetFromMetadataAttributes extends BaseGetFromMetadataAttributes
{
    use ExtendableAttributeNames;

    protected static function fromDirectory(): string
    {
        return self::usingDirectory('Groups');
    }
}
