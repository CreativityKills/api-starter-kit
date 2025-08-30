<?php

declare(strict_types=1);

namespace App\Http\Documentation\Responses;

use Attribute;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @implements Arrayable<string, mixed>
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class GenericResponse implements Arrayable
{
    protected bool $addRateLimitingHeaders = true;

    public function __construct(protected readonly int $status)
    {
    }

    /**
     * @return array{status: int, content: string, description: string, headers: array<string, string>}
     * @throws \JsonException
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'content' => $this->getContent(),
            'description' => $this->getDescription(),
            'headers' => [
                'Content-Type' => 'application/json',
                ...($this->addRateLimitingHeaders ? [
                    'X-Rate-Limit-Limit' => '60',
                    'X-Rate-Limit-Remaining' => '59',
                ] : []),
            ],
        ];
    }

    /**
     * @throws \JsonException
     */
    protected function getContent(): string
    {
        $content = match ($this->status) {
            Response::HTTP_BAD_REQUEST => ['message' => 'Bad Request'],
            Response::HTTP_UNAUTHORIZED => ['message' => 'Unauthorized'],
            Response::HTTP_FORBIDDEN => ['message' => 'Forbidden'],
            Response::HTTP_NOT_FOUND => ['message' => 'Not Found'],
            Response::HTTP_TOO_MANY_REQUESTS => ['message' => 'Too Many Requests'],
            Response::HTTP_UNPROCESSABLE_ENTITY => json_encode([
                'message' => 'The given data was invalid.',
                'errors' => $this->getBodyParametersFromFormRequest() ?? [
                        'field_name' => ['The given data was invalid.'],
                    ],
            ], JSON_THROW_ON_ERROR),
            Response::HTTP_NO_CONTENT, Response::HTTP_CREATED => '',
            default => ['message' => 'Unknown status'],
        };

        return $this->parseContent($content);
    }

    /**
     * @return array<array<string>>|null
     */
    protected function getBodyParametersFromFormRequest(): ?array
    {
        $request = $this->getFormRequest();
        if (
            !$request ||
            (!method_exists($request, 'bodyParameters') && !method_exists($request, 'queryParameters'))
        ) {
            return null;
        }

        $parameters = [];
        if (method_exists($request, 'bodyParameters')) {
            foreach (array_keys($request->bodyParameters()) as $key) {
                $parameters[$key][] = "This is a sample error for $key";
            }
        }

        if (method_exists($request, 'queryParameters')) {
            foreach (array_keys($request->queryParameters()) as $key) {
                $parameters[$key][] = "This is a sample error for $key";
            }
        }

        return $parameters;
    }

    protected function getFormRequest(): ?FormRequest
    {
        return null;
    }

    protected function getDescription(): string
    {
        return match ($this->status) {
            Response::HTTP_OK => 'Successful',
            Response::HTTP_CREATED => 'Created',
            Response::HTTP_NO_CONTENT => 'No Content',
            Response::HTTP_UNAUTHORIZED => 'Unauthorized',
            Response::HTTP_TOO_MANY_REQUESTS => 'Too Many Requests',
            Response::HTTP_FORBIDDEN => 'Forbidden',
            Response::HTTP_NOT_FOUND => 'Not Found',
            Response::HTTP_UNPROCESSABLE_ENTITY => 'Validation Failed',
            Response::HTTP_BAD_REQUEST => 'Bad Request',
            default => 'Unknown status',
        };
    }

    /**
     * @param  array<array-key, mixed>|string  $content
     * @throws \JsonException
     */
    protected function parseContent(array|string $content): string
    {
        return is_string($content) ? $content : json_encode($content, JSON_THROW_ON_ERROR);
    }
}
