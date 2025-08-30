<?php

declare(strict_types=1);

namespace Yulo\Extensions\Scribe\Writing\Postman\Processors;

use Closure;

class CreateTokenEndpointProcessor implements EndpointProcessor
{
    public function __invoke(array $endpoint, Closure $next): array
    {
        $endpoint['request'] = [
            ...$endpoint['request'],
            'body' => [
                ...$endpoint['request']['body'],
                'raw' => '{"email":"{{email}}","password":"{{password}}","device":"Postman"}',
            ],
        ];

        return $next($endpoint);
    }
}
