<?php

declare(strict_types=1);

namespace App\Http\Documentation\Responses;

use Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class UnprocessableEntityResponse extends GenericResponse
{
    /**
     * @param  class-string<\Illuminate\Foundation\Http\FormRequest>|null  $requestClass
     */
    public function __construct(
        protected ?string $requestClass = null
    ) {
        parent::__construct(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function getFormRequest(): ?FormRequest
    {
        return $this->requestClass ? app($this->requestClass) : null;
    }
}
