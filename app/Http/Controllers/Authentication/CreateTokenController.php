<?php

declare(strict_types=1);

namespace Yulo\Http\Controllers\Authentication;

use Illuminate\Http\JsonResponse;
use Yulo\Http\Documentation\Groups\AuthenticationGroup;
use Yulo\Http\Requests\Authentication\CreateTokenRequest;
use Yulo\Http\Documentation\Responses\RateLimitedResponse;
use Yulo\Http\Documentation\Responses\UnprocessableEntityResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Yulo\Http\Documentation\Responses\Authentication\AccessTokenResponse;

#[AuthenticationGroup]
class CreateTokenController
{
    /**
     * Create Access Token
     *
     * Authenticates the user and creates a new access & refresh token for the user to make API requests.
     */
    #[AccessTokenResponse, RateLimitedResponse, UnprocessableEntityResponse]
    public function __invoke(CreateTokenRequest $request): JsonResponse
    {
        return app(AuthenticatedSessionController::class)->store($request);
    }
}
