<?php

declare(strict_types=1);

namespace Yulo\Extensions\Scribe\Writing\Postman\Processors;

use Closure;
use stdClass;

class RefreshTokenEndpointProcessor implements EndpointProcessor
{
    public function __invoke(array $endpoint, Closure $next): array
    {
        $endpoint = [
            ...$endpoint,
            'event' => [
                ...($endpoint['event'] ?? []),
                [
                    'listen' => 'test',
                    'script' => [
                        'exec' => [
                            'const response = pm.response.json()',
                            'if (response.access_token) {',
                            '    // Store the access token and refresh token in environment variables',
                            '    pm.environment.set("access_token", response.access_token)',
                            '    pm.environment.set("access_token_expires_at", response.access_token_expires_at)',
                            '    pm.environment.set("refresh_token", response.refresh_token)',
                            '    pm.environment.set("refresh_token_expires_at", response.refresh_token_expires_at)',
                            '}',
                        ],
                        'type' => 'text/javascript',
                        'packages' => new stdClass(),
                    ],
                ],
            ],
            'request' => [
                ...$endpoint['request'],
                'body' => [
                    ...$endpoint['request']['body'],
                    'raw' => '{"token":"{{access_token}}","device":"Postman"}',
                ],
                'auth' => [
                    'type' => 'bearer',
                    'bearer' => [
                        ['key' => 'token', 'value' => '{{refresh_token}}', 'type' => 'string'],
                    ],
                ],
            ],
        ];

        return $next($endpoint);
    }
}
