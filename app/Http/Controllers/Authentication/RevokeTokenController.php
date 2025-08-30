<?php

declare(strict_types=1);

namespace App\Http\Controllers\Authentication;

use App\Models\User;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use App\Http\Documentation\Groups\AuthenticationGroup;
use App\Http\Documentation\Responses\NoContentResponse;
use App\Http\Requests\Authentication\RevokeTokenRequest;
use App\Http\Documentation\Responses\RateLimitedResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

#[AuthenticationGroup]
class RevokeTokenController
{
    /**
     * Revoke Access Token
     *
     * Revokes the access token for the user. It's also possible to refresh all tokens for the user by passing the expected
     * body parameter.
     *
     * > [!important]
     * > Token must have the `system:revoke-all-access-tokens` ability when setting `all` to `true`
     */
    #[Authenticated]
    #[NoContentResponse, RateLimitedResponse]
    public function __invoke(RevokeTokenRequest $request, #[CurrentUser] User $user): mixed
    {
        if ($request->revokeAll()) {
            $user->tokens()->delete();
        }

        return app(AuthenticatedSessionController::class)->destroy($request);
    }
}
