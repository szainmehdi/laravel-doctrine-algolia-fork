<?php

declare(strict_types=1);

namespace Zain\LaravelDoctrine\Algolia;

use Algolia\AlgoliaSearch\Support\UserAgent;
use Illuminate\Support\ServiceProvider;

class AlgoliaServiceProvider extends ServiceProvider
{
    public const VERSION = '0.1';

    public function boot(): void
    {
        UserAgent::addCustomUserAgent('Laravel Doctrine Algolia', self::VERSION);
        UserAgent::addCustomUserAgent('Laravel', $this->app->version());
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                // ImportCommand::class
            ]);
        }
    }
}
