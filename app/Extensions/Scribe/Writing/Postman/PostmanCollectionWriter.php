<?php

declare(strict_types=1);

namespace App\Extensions\Scribe\Writing\Postman;

use stdClass;
use Illuminate\Pipeline\Pipeline;
use Knuckles\Camel\Extraction\Response;
use Knuckles\Camel\Output\OutputEndpointData;
use Knuckles\Scribe\Tools\DocumentationConfig;
use Knuckles\Scribe\Writing\PostmanCollectionWriter as BasePostmanCollectionWriter;

class PostmanCollectionWriter extends BasePostmanCollectionWriter
{
    /**
     * @var array<int, string>
     */
    protected array $statusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    ];

    public static function make(): self
    {
        return new self(new DocumentationConfig(config('scribe')));
    }

    protected function getHttpMethod(OutputEndpointData $endpoint): string
    {
        $method = $endpoint->httpMethods[0];

        if (
            (in_array('PUT', $endpoint->httpMethods) || in_array('PATCH', $endpoint->httpMethods)) &&
            isset($endpoint->bodyParameters['formdata'])
        ) {
            $method = 'POST';
        }

        return $method;
    }

    /**
     * @return array<string, mixed>
     */
    protected function generateEndpointItem(OutputEndpointData $endpoint): array
    {
        $endpointItem = parent::generateEndpointItem($endpoint);
        $endpointItem['response'] = $this->getResponses($endpoint);

        return $endpointItem;
    }

    /**
     * @return array<string, mixed>
     */
    protected function generateUrlObject(OutputEndpointData $endpointData): array
    {
        $base = parent::generateUrlObject($endpointData);
        $base['path'] = preg_replace('/v\d+/', '{{version}}', $base['path']);

        $variables = [];
        foreach ($base['variable'] ?? [] as $variable) {
            if ($variable['id'] === 'organization_id') {
                $variable['value'] = '{{organization_id}}';
            }

            $variables[] = $variable;
        }
        $base['variable'] = $variables;

        return $base;
    }

    /**
     * @return array<string, mixed>
     */
    private function defineVariable(string $key, string $value): array
    {
        return [
            'id' => $key,
            'type' => 'string',
            ...compact('key', 'value'),
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $groupedEndpoints
     * @return array<string, mixed>
     */
    public function generatePostmanCollection(array $groupedEndpoints): array
    {
        $collection = parent::generatePostmanCollection($groupedEndpoints);
        $collection['variable'] = [
            ...$collection['variable'],
            $this->defineVariable('version', 'v1'),
            $this->defineVariable('email', 'neo@yulo.test'),
            $this->defineVariable('password', 'password'),
        ];

        $collection = $this->addPreRequestScriptToCollection($collection);
        $collection = $this->processCollectionEndpoints($collection);

        if (data_get($collection, 'auth.type') === 'bearer') {
            $collection['auth']['bearer'][] = [
                'key' => 'token',
                'value' => '{{access_token}}',
                'type' => 'string',
            ];
        }

        return $collection;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getResponses(OutputEndpointData $endpoint): array
    {
        return $endpoint->responses->map(function (Response $response) use ($endpoint) {
            $headers = [];

            foreach ($response->headers as $header => $value) {
                $headers[] = ['key' => $header, 'value' => $value];
            }

            $status = $this->statusCodes[$response->status] ?? null;

            return [
                'name' => $this->getResponseDescription($response),
                ...(isset($status) ? ['status' => $status] : []),
                'code' => $response->status,
                'header' => $headers,
                '_postman_previewlanguage' => 'json',
                'body' => $this->parseResponseContent($response->content),
                'originalRequest' => [
                    'method' => $this->getHttpMethod($endpoint),
                    'header' => $this->resolveHeadersForEndpoint($endpoint),
                    'url' => $this->generateUrlObject($endpoint),
                ],
            ];
        })->toArray();
    }

    private function isJsonString(?string $string): bool
    {
        if (blank($string)) {
            return false;
        }

        return json_validate($string);
    }

    private function parseResponseContent(?string $json): string
    {
        if (!$json) {
            return '';
        }

        if (!$this->isJsonString($json)) {
            return $json;
        }

        $decoded = json_decode($json, true);
        $encoded = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return json_last_error() === JSON_ERROR_NONE ? (string) $encoded : $json;
    }

    /**
     * @param  array<string, mixed>  $collection
     * @return array<string, mixed>
     */
    protected function addPreRequestScriptToCollection(array $collection): array
    {
        $collection['event'] = [
            [
                'listen' => 'prerequest',
                'script' => [
                    'type' => 'text/javascript',
                    'packages' => new stdClass(),
                    'exec' => [
                        '// --------------------------------------------------------------------------------',
                        '// Variables',
                        '// --------------------------------------------------------------------------------',
                        '',
                        "const baseUrl = pm.collectionVariables.get('baseUrl') + '/' + pm.variables.get('version')",
                        '',
                        "const email = pm.environment.get('email')",
                        "const password = pm.environment.get('password')",
                        '',
                        "const access_token = pm.environment.get('access_token')",
                        "const access_token_expiration = pm.environment.get('access_token_expires_at')",
                        "const refresh_token = pm.environment.get('refresh_token')",
                        "const refrest_token_expiration = pm.environment.get('refresh_token_expires_at')",
                        '',
                        '',
                        '// --------------------------------------------------------------------------------',
                        '// Check for need to create new one or to refresh',
                        '// --------------------------------------------------------------------------------',
                        '',
                        'if (access_token && access_token_expiration) {',
                        '    const currentTimestamp = Math.floor(Date.now() / 1000)',
                        '',
                        '    if (access_token_expiration > currentTimestamp) {',
                        '        return;',
                        '    }',
                        '}',
                        '',
                        '',
                        '// --------------------------------------------------------------------------------',
                        '// Create Request',
                        '// --------------------------------------------------------------------------------',
                        '',
                        '',
                        'const createRequest = () => ({',
                        "    url: baseUrl + '/auth/token/create',",
                        "    method: 'POST',",
                        '    header: {',
                        "        'Content-Type': 'application/json',",
                        '    },',
                        '    body: {',
                        "        mode: 'raw',",
                        "        raw: JSON.stringify({ email, password, device: 'Postman' })",
                        '    }',
                        '});',
                        '',
                        'const refreshRequest = () => ({',
                        "    url: baseUrl + '/auth/token/refresh',",
                        "    method: 'POST',",
                        '    header: {',
                        "        'Content-Type': 'application/json',",
                        "        'Authorization': 'Bearer ' + refresh_token,",
                        '    },',
                        '    body: {',
                        "        mode: 'raw',",
                        '        raw: JSON.stringify({ token: access_token })',
                        '    }',
                        '})',
                        '',
                        '',
                        'pm.sendRequest(createRequest(), function (err, response) {',
                        '    if (err) {',
                        "        console.error('Error:', err);",
                        '        return;',
                        '    }',
                        '    ',
                        '    const res = response.json();',
                        '    if (response.code > 399) {',
                        '        console.error({ ...res })',
                        '        return',
                        '    }',
                        '',
                        '',
                        '    const nowTimestamp = Math.floor(Date.now() / 1000)',
                        '',
                        "    pm.environment.set('access_token', res.access_token);",
                        "    pm.environment.set('access_token_expires_at', nowTimestamp + res.access_token_expires_at)",
                        "    pm.environment.set('refresh_token', res.refresh_token)",
                        "    pm.environment.set('refresh_token_expires_at', nowTimestamp + res.refresh_token_expires_at)",
                        '});',
                    ],
                ],
            ],
        ];

        return $collection;
    }

    /**
     * @param  array<string, mixed>  $collection
     * @return array<string, mixed>
     */
    protected function processCollectionEndpoints(array $collection): array
    {
        return app(PostmanEndpointProcessor::class)->processEndpoints($collection, function ($rawUrl, $endpoint) {
            $customizers = config('scribe.postman.writer.endpoint_callbacks', []);

            foreach ($customizers as $customizerUrl => $callbacks) {
                if (str_ends_with($rawUrl, $customizerUrl) && filled($callbacks)) {
                    return app(Pipeline::class)->send($endpoint)->through($callbacks)->thenReturn();
                }
            }

            return $endpoint;
        });
    }
}
