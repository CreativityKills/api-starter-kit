<?php

declare(strict_types=1);

namespace App\Http\Documentation\Groups;

use Attribute;

/**
 * @required
 */
#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class GenericSubgroup
{
    public function __construct(
        public string $name,
        public ?string $description = '',
    ) {
    }

    /**
     * @return array{subgroup: string, subgroupDescription: string|null}
     */
    public function toArray(): array
    {
        return [
            'subgroup' => $this->name,
            'subgroupDescription' => $this->description,
        ];
    }
}
