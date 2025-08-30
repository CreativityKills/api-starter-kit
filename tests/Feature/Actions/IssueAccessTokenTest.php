<?php

declare(strict_types=1);

use App\Models\User;
use App\Data\Enums\AccessLevel;
use App\Data\IssueAccessTokenDto;
use App\Data\IssuedAccessTokenDto;
use App\Events\IssuedAccessTokenEvent;
use App\Events\IssuingAccessTokenEvent;
use App\Data\Enums\AccessLevelAbilities;
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
        ->and($token->refreshToken->accessToken->abilities)->toEqualCanonicalizing([AccessLevelAbilities::SYSTEM__REFRESH_ACCESS_TOKEN->value])
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
            AccessLevelAbilities::AI__TEXT_IMPROVEMENT->value,
            AccessLevelAbilities::MODULE__READING_COMPREHENSION->value,
            AccessLevelAbilities::MODULE__LANGUAGE_ELEMENTS->value,
            AccessLevelAbilities::MODULE__WRITTEN_EXPRESSION->value,
        ],
    ],
    AccessLevel::BASIC->value => [
        'level' => AccessLevel::BASIC,
        'abilities' => [
            AccessLevelAbilities::AI__TEXT_IMPROVEMENT->value,
            AccessLevelAbilities::AI__LEARNING_RECOMMENDATIONS->value,
            AccessLevelAbilities::MODULE__READING_COMPREHENSION->value,
            AccessLevelAbilities::MODULE__LISTENING_COMPREHENSION->value,
            AccessLevelAbilities::MODULE__LANGUAGE_ELEMENTS->value,
            AccessLevelAbilities::MODULE__WRITTEN_EXPRESSION->value,
            AccessLevelAbilities::MODULE__ORAL_EXPRESSION->value,
            AccessLevelAbilities::EXAM__TIMED_MODULE_PRACTICE->value,
            AccessLevelAbilities::EXAM__FULL_MODULE_SIMULATION->value,
        ],
    ],
]);
