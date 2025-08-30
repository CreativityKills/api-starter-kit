<?php

declare(strict_types=1);

use App\Models\User;
use App\Data\Enums\Abilities;
use App\Data\Enums\AccessLevel;
use App\Data\IssueAccessTokenDto;
use App\Data\IssuedAccessTokenDto;
use App\Events\IssuedAccessTokenEvent;
use App\Events\IssuingAccessTokenEvent;
use App\Actions\Sanctum\IssueAccessToken;

// ---------------------------------------------------------------------------------------------------------------------
// Setup
// ---------------------------------------------------------------------------------------------------------------------

uses(
    Illuminate\Foundation\Testing\LazilyRefreshDatabase::class,
)->group('actions', 'sanctum');

// ---------------------------------------------------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------------------------------------------------

it('can issue access token', function (AccessLevel $level, array $abilities, ?DateTimeInterface $expiresAt = null) {
    Event::fake([IssuingAccessTokenEvent::class, IssuedAccessTokenEvent::class]);

    $user = User::factory()->createOneQuietly();
    $requestDto = new IssueAccessTokenDto(user: $user, accessLevel: $level, expiresAt: $expiresAt);

    $token = app(IssueAccessToken::class)->handle($requestDto);
    $expiresAt ??= now()->addMinutes(config('sanctum.expiration'));
    $refreshExpiresAt = now()->addHours(config('sanctum.refresh_expiration'));

    expect($token)->toBeInstanceOf(IssuedAccessTokenDto::class)
        ->and($token->accessToken->accessToken->abilities)->toEqualCanonicalizing($abilities)
        ->and($token->accessToken->accessToken->expires_at?->format('Y-m-d H:i'))->toEqual($expiresAt->format('Y-m-d H:i'))
        ->and($token->refreshToken->accessToken->abilities)->toEqualCanonicalizing([Abilities::SYSTEM__REFRESH_ACCESS_TOKEN->value])
        ->and($token->refreshToken->accessToken->expires_at?->format('Y-m-d H:i'))->toEqual($refreshExpiresAt->format('Y-m-d H:i'));

    Event::assertDispatched(IssuingAccessTokenEvent::class);
    Event::assertDispatched(IssuedAccessTokenEvent::class);
})->with(fn () => [
    'Full' => [
        'level' => AccessLevel::FULL,
        'abilities' => Abilities::for(AccessLevel::FULL),
        'expiresAt' => now()->addMinute(),
    ],
]);
