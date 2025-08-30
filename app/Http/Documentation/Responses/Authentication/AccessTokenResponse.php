<?php

declare(strict_types=1);

namespace App\Http\Documentation\Responses\Authentication;

use Attribute;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Documentation\Responses\GenericResponse;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class AccessTokenResponse extends GenericResponse
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_OK);
    }

    protected function getContent(): string
    {
        return $this->parseContent([
            'access_token' => '32|kKZv3U9MrlGHaUXwM7pXW232sHcxRo12Kb3VfuEYf3fab36b',
            'access_token_expires_at' => '2025-08-30T09:09:35+00:00',
            'refresh_token' => '33|UbOiq6LjuovCTGb7TOVAB9sTDVUDpERzRZYJNoa4e5693ad7',
            'refresh_token_expires_at' => '2025-08-31T08:39:35+00:00',
        ]);
    }
}
