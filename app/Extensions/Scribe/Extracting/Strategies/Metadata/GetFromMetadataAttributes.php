<?php

declare(strict_types=1);

namespace Yulo\Extensions\Scribe\Extracting\Strategies\Metadata;

use Yulo\Extensions\Scribe\Concerns\ExtendableAttributeNames;
use Knuckles\Scribe\Extracting\Strategies\Metadata\GetFromMetadataAttributes as BaseGetFromMetadataAttributes;

class GetFromMetadataAttributes extends BaseGetFromMetadataAttributes
{
    use ExtendableAttributeNames;

    protected static function fromDirectory(): string
    {
        return self::usingDirectory('Metadata');
    }

    protected static function namespace(string $appending = ''): string
    {
        return self::usingNamespace('Metadata', $appending);
    }
}
