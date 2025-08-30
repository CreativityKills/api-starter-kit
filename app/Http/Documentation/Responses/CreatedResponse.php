<?php

declare(strict_types=1);

namespace Yulo\Http\Documentation\Responses;

use Attribute;
use Symfony\Component\HttpFoundation\Response;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class CreatedResponse extends GenericResponse
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_CREATED);
    }
}
