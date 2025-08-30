<?php

declare(strict_types=1);

use Laravel\Fortify\RoutePath;
use App\Data\Enums\AccessLevel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication;

// --------------------------------------------------------------------------------------------------------------------
// Authentication
// --------------------------------------------------------------------------------------------------------------------

Route::prefix('auth')->group(function () {
    $loginLimiter = config('fortify.limiters.login');
    $refreshLimiter = config('fortify.limiters.refresh-token');

    Route::post(RoutePath::for('login', '/token/create'), Authentication\CreateTokenController::class)
        ->name('token.store')
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),
            $loginLimiter ? "throttle:$loginLimiter" : null,
        ]));

    Route::post(RoutePath::for('refresh-token', '/token/refresh'), Authentication\RefreshTokenController::class)
        ->name('token.refresh')
        ->middleware(array_filter([
            'auth:sanctum',
            $refreshLimiter ? "throttle:$refreshLimiter" : null,
            AccessLevel::canRefreshAccessTokenMiddleware(),
        ]));

    Route::post(RoutePath::for('logout', '/token/revoke'), Authentication\RevokeTokenController::class)
        ->name('token.revoke')
        ->middleware([
            'auth:sanctum',
            config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'),
        ]);
});
