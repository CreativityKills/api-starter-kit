<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Models\User;
use App\Data\Enums\AccessLevel;
use App\Data\IssueAccessTokenDto;
use App\Actions\Sanctum\IssueAccessToken;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @throws \Exception
     */
    public function toResponse($request)
    {
        $user = $request->user();
        $name = $request->get('device');

        assert($user instanceof User);

        $issueRequestDto = new IssueAccessTokenDto(user: $user, accessLevel: AccessLevel::FULL, name: $name);
        $issuedToken = resolve(IssueAccessToken::class)->handle($issueRequestDto);

        return response()->json([
            'access_token' => $issuedToken->getPlainTextAccessToken(),
            'access_token_expires_at' => $issuedToken->getAccessTokenExpiration()?->getTimestamp(),
            'refresh_token' => $issuedToken->getPlainTextRefreshToken(),
            'refresh_token_expires_at' => $issuedToken->getRefreshTokenExpiration()?->getTimestamp(),
        ]);
    }
}
