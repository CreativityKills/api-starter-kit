<?php

declare(strict_types=1);

namespace Yulo\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateIfNotLocal extends Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (app()->isLocal()) {
            return $next($request);
        }

        return parent::handle($request, $next, ...$guards);
    }
}
