<?php

declare(strict_types=1);

namespace Yulo\Providers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Yulo\Http\Responses\LoginResponse;
use Illuminate\Support\ServiceProvider;
use Yulo\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Yulo\Actions\Fortify\ResetUserPassword;
use Yulo\Actions\Fortify\UpdateUserPassword;
use Yulo\Actions\Fortify\UpdateUserProfileInformation;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Fortify::ignoreRoutes();

        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
    }

    public function boot(): void
    {
        $this->setActions();
        $this->setRateLimits();
    }

    protected function setActions(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    }

    protected function setRateLimits(): void
    {
        RateLimiter::for('refresh-token', fn (Request $request) => (
            Limit::perMinute(5)->by(
                Str::transliterate($request->user()?->getAuthIdentifier() ?? '').'|'.$request->ip()
            )
        ));

        RateLimiter::for('login', fn (Request $request) => (
            Limit::perMinute(5)->by(
                Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip())
            )
        ));

        RateLimiter::for('two-factor', fn (Request $request) => (
            Limit::perMinute(5)->by(
                $request->session()->get('login.id')
            )
        ));
    }
}
