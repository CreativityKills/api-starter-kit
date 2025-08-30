<?php

declare(strict_types=1);

namespace Yulo\Http\Controllers\Authentication;

use Knuckles\Scribe\Attributes\Header;
use Yulo\Actions\Sanctum\IssueAccessToken;
use Laravel\Fortify\Contracts\LoginResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Symfony\Component\HttpFoundation\Response;
use Yulo\Http\Documentation\Groups\AuthenticationGroup;
use Yulo\Http\Documentation\Responses\RateLimitedResponse;
use Yulo\Http\Documentation\Responses\UnauthorizedResponse;
use Yulo\Http\Documentation\Responses\UnprocessableEntityResponse;
use Yulo\Http\Requests\Authentication\RefreshTokenRequest as Request;
use Yulo\Http\Documentation\Responses\Authentication\AccessTokenResponse;

#[AuthenticationGroup]
final class RefreshTokenController
{
    /**
     * Refresh Access Token
     *
     * Refreshes the user's access token using the refresh token. To refresh an access token, you must pass the refresh
     * token in the Authorization header and the access token in the body of the request using the `token` parameter.
     *
     * > [!warning]
     * > This will **_revoke both the refresh & access_** token being sent, and generate a new one.
     */
    #[Authenticated]
    #[AccessTokenResponse, UnauthorizedResponse, RateLimitedResponse, UnprocessableEntityResponse]
    public function __invoke(IssueAccessToken $issueAccessToken, Request $request, LoginResponse $loginResponse): Response
    {
        $request->getUserAccessToken()->delete();
        $request->getUserRefreshToken()->delete();

        return $loginResponse->toResponse($request);
    }
}
