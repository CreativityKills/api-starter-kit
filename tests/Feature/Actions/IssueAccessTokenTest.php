<?php

declare(strict_types=1);

use App\Models\User;
use App\Data\Enums\AccessLevel;
use App\Data\IssueAccessTokenDto;
use App\Data\IssuedAccessTokenDto;
use App\Events\IssuedAccessTokenEvent;
use App\Events\IssuingAccessTokenEvent;
use App\Data\Enums\Abilities;
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
})->with(fn() => [
    AccessLevel::FULL->value => [
        'level' => AccessLevel::FULL,
        'abilities' => ['*'],
        'expiresAt' => now()->addMinute(),
    ],
    AccessLevel::TRIAL->value => [
        'level' => AccessLevel::TRIAL,
        'abilities' => [
            Abilities::AI__TEXT_IMPROVEMENT->value,
            Abilities::MODULE__READING_COMPREHENSION->value,
            Abilities::MODULE__LANGUAGE_ELEMENTS->value,
            Abilities::MODULE__WRITTEN_EXPRESSION->value,
        ],
    ],
    AccessLevel::BASIC->value => [
        'level' => AccessLevel::BASIC,
        'abilities' => [
            Abilities::AI__TEXT_IMPROVEMENT->value,
            Abilities::AI__LEARNING_RECOMMENDATIONS->value,
            Abilities::MODULE__READING_COMPREHENSION->value,
            Abilities::MODULE__LISTENING_COMPREHENSION->value,
            Abilities::MODULE__LANGUAGE_ELEMENTS->value,
            Abilities::MODULE__WRITTEN_EXPRESSION->value,
            Abilities::MODULE__ORAL_EXPRESSION->value,
            Abilities::EXAM__TIMED_MODULE_PRACTICE->value,
            Abilities::EXAM__FULL_MODULE_SIMULATION->value,
        ],
    ],
]);
