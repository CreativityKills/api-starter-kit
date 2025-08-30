<?php

declare(strict_types=1);

namespace App\Http\Documentation\Responses;

use Attribute;
use Symfony\Component\HttpFoundation\Response;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class SimpleResponse extends GenericResponse
{
    /**
     * @param  string|array<array-key, mixed>  $content
     */
    public function __construct(protected string|array $content)
    {
        parent::__construct(Response::HTTP_OK);
    }

    protected function getContent(): string
    {
        return $this->parseContent($this->content);
    }
}
