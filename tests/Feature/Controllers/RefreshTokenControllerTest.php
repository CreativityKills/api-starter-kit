<?php

declare(strict_types=1);

use App\Models\User;
use App\Data\Enums\AccessLevel;
use App\Data\IssueAccessTokenDto;
use App\Actions\Sanctum\IssueAccessToken;
use Illuminate\Database\Eloquent\ModelNotFoundException;

// ---------------------------------------------------------------------------------------------------------------------
// Setup
// ---------------------------------------------------------------------------------------------------------------------

uses(
    Illuminate\Foundation\Testing\LazilyRefreshDatabase::class,
)->group('actions', 'sanctum');

// ---------------------------------------------------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------------------------------------------------

it('will refresh the access token', function () {
    $user = User::factory()->createOneQuietly();

    expect($user->tokens()->count())->toBe(0);

    $oldToken = app(IssueAccessToken::class)->handle(
        new IssueAccessTokenDto(user: $user, accessLevel: AccessLevel::FULL)
    );

    Pest\Laravel\postJson(
        uri: '/v1/token/refresh',
        data: ['token' => $oldToken->getPlainTextAccessToken()],
        headers: ['Authorization' => 'Bearer '.$oldToken->getPlainTextRefreshToken()]
    )
        ->assertOk()
        ->assertJsonStructure(['access_token', 'access_token_expires_at', 'refresh_token', 'refresh_token_expires_at']);

    expect(fn() => $oldToken->getAccessToken()->refresh())->toThrow(ModelNotFoundException::class)
        ->and(fn() => $oldToken->getRefreshToken()->refresh())->toThrow(ModelNotFoundException::class)
        ->and($user->tokens()->count())->toBe(2);
});

it('will fail to refresh access token when a valid, but wrong token is used', function () {
    $user = User::factory()->createOneQuietly();

    $oldToken = app(IssueAccessToken::class)->handle(
        new IssueAccessTokenDto(user: $user, accessLevel: AccessLevel::FULL)
    );

    Pest\Laravel\postJson(
        uri: '/v1/token/refresh',
        data: ['token' => $oldToken->getPlainTextRefreshToken()],
        headers: ['Authorization' => 'Bearer '.$oldToken->getPlainTextAccessToken()]
    )->assertForbidden();
});

it('will fail with valid refresh token is passed but invalid access token is supplied', function () {
    $user = User::factory()->createOneQuietly();

    $oldToken = app(IssueAccessToken::class)->handle(
        new IssueAccessTokenDto(user: $user, accessLevel: AccessLevel::FULL)
    );

    Pest\Laravel\postJson(
        uri: '/v1/token/refresh',
        data: ['token' => sprintf('%s.', $oldToken->getPlainTextAccessToken())],      // add a dot to the access token
        headers: ['Authorization' => 'Bearer '.$oldToken->getPlainTextRefreshToken()]
    )->assertForbidden();
});
