<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | This value controls the number of minutes until an issued token will be
    | considered expired. This will override any values set in the token's
    | "expires_at" attribute, but first-party sessions are not affected.
    |
    */

    'expiration' => env('SANCTUM_TOKEN_EXPIRATION_MINUTES', 30),

    'refresh_expiration' => env('SANCTUM_REFRESH_TOKEN_EXPIRATION_HOURS', 24),

];
