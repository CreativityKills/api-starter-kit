<?php

declare(strict_types=1);

namespace Yulo\Providers;

use Illuminate\Support\ServiceProvider;
use Yulo\Extensions\Scribe\Writing\Writer;
use Knuckles\Scribe\Writing\Writer as BaseWriter;
use Yulo\Extensions\Scribe\Writing\Postman\PostmanCollectionWriter;
use Knuckles\Scribe\Writing\PostmanCollectionWriter as BasePostmanCollectionWriter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BaseWriter::class, Writer::class);
        $this->app->bind(BasePostmanCollectionWriter::class, fn () => PostmanCollectionWriter::make());
    }

    public function boot(): void
    {
        //
    }
}
