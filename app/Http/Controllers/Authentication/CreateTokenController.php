<?php

declare(strict_types=1);

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\JsonResponse;
use App\Http\Documentation\Groups\AuthenticationGroup;
use App\Http\Requests\Authentication\CreateTokenRequest;
use App\Http\Documentation\Responses\RateLimitedResponse;
use App\Http\Documentation\Responses\UnprocessableEntityResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Documentation\Responses\Authentication\AccessTokenResponse;

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
