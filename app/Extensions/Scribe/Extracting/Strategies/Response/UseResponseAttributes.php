<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Extracting\Strategies\Response;

use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\ResponseFromTransformer;
use App\Extensions\Scribe\Concerns\ExtendableAttributeNames;
use Knuckles\Scribe\Extracting\Strategies\Responses\UseResponseAttributes as BaseUseResponseAttributes;

class UseResponseAttributes extends BaseUseResponseAttributes
{
    use ExtendableAttributeNames;

    protected static function fromDirectory(): string
    {
        return self::usingDirectory('Responses');
    }

    /**
     * @param  array<Response|ResponseFromFile|ResponseFromApiResource|ResponseFromTransformer>  $attributesOnMethod
     * @param  array<array-key, mixed>  $attributesOnFormRequest
     * @throws \JsonException
     * @return array<array-key, mixed>|null
     */
    protected function extractFromAttributes(
        ExtractedEndpointData $endpointData,
        array $attributesOnMethod,
        array $attributesOnFormRequest = [],
        array $attributesOnController = []
    ): ?array {
        $responses = [];

        foreach ([...$attributesOnController, ...$attributesOnFormRequest, ...$attributesOnMethod] as $attributeInstance) {
            if ($attributeInstance instanceof ResponseFromApiResource) {
                $responses[] = $this->getApiResourceResponse($attributeInstance);
                continue;
            }

            $responses[] = match (get_class($attributeInstance)) {
                ResponseFromTransformer::class => $this->getTransformerResponse($attributeInstance),
                default => $attributeInstance->toArray(),
            };
        }

        return $responses;
    }
}
