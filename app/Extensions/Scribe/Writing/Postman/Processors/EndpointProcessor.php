<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Writing\Postman\Processors;

use Closure;

interface EndpointProcessor
{
    /**
     * @param  array<string, mixed>  $endpoint
     * @return array<string, mixed>
     */
    public function __invoke(array $endpoint, Closure $next): array;
}
