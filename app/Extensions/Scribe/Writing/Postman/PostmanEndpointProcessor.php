<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Writing\Postman;

class PostmanEndpointProcessor
{
    /**
     * Process all endpoints in a Postman collection
     *
     * @param  array<string, mixed>  $collection
     * @return array<string, mixed>
     */
    public function processEndpoints(array $collection, callable $callback): array
    {
        if (isset($collection['item'])) {
            $collection['item'] = $this->processItems($collection['item'], $callback);
        }

        return $collection;
    }

    /**
     * Recursively process items (folders and endpoints)
     *
     * @param  array<string, mixed>  $items
     * @return array<int, mixed>
     */
    private function processItems(array $items, callable $callback): array
    {
        $processedItems = [];

        foreach ($items as $item) {
            $processedItems[] = $this->processItem($item, $callback);
        }

        return $processedItems;
    }

    /**
     * Process a single item - either folder or endpoint
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function processItem(array $item, callable $callback): array
    {
        // Check if this is an endpoint (has request object)
        if (isset($item['request'])) {
            return $this->applyEndpointCallback($item, $callback);
        }

        // Check if this is a folder (has item array)
        if (isset($item['item'])) {
            return $this->processFolder($item, $callback);
        }

        // Return unchanged if neither
        return $item;
    }

    /**
     * Apply callback to an endpoint
     *
     * @param  array<string, mixed>  $endpoint
     * @return array<string, mixed>
     */
    private function applyEndpointCallback(array $endpoint, callable $callback): array
    {
        $rawUrl = $this->extractRawUrl($endpoint);

        // Apply callback - expects (rawUrl, endpointData) and returns modified endpointData
        return $callback($rawUrl, $endpoint);
    }

    /**
     * Process a folder by recursively processing its items
     *
     * @param  array<string, mixed>  $folder
     * @return array<string, mixed>
     */
    private function processFolder(array $folder, callable $callback): array
    {
        $folder['item'] = $this->processItems($folder['item'], $callback);

        return $folder;
    }

    /**
     * Extract raw URL from endpoint data
     *
     * @param  array<string, mixed>  $endpoint
     */
    private function extractRawUrl(array $endpoint): ?string
    {
        return $endpoint['request']['url']['raw'] ?? $endpoint['request']['url'] ?? null;
    }

    /**
     * Update all endpoints with a specific field
     *
     * @param  array<string, mixed>  $collection
     * @return array<string, mixed>
     */
    public function setEndpointField(array $collection, string $field, mixed $value): array
    {
        return $this->processEndpoints($collection, function ($rawUrl, $endpoint) use ($field, $value) {
            $endpoint[$field] = $value;

            return $endpoint;
        });
    }

    /**
     * Transform URLs in all endpoints
     *
     * @param  array<string, mixed>  $collection
     * @return array<string, mixed>
     */
    public function transformEndpointUrls(array $collection, callable $urlTransformer): array
    {
        return $this->processEndpoints($collection, function ($rawUrl, $endpoint) use ($urlTransformer) {
            if (isset($endpoint['request']['url']['raw'])) {
                $endpoint['request']['url']['raw'] = $urlTransformer($rawUrl);
            }

            return $endpoint;
        });
    }
}
