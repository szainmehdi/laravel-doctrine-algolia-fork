<?php

declare(strict_types=1);

namespace Zain\LaravelDoctrine\Algolia;

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\Support\UserAgent;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Zain\LaravelDoctrine\Algolia\Command\SearchImportCommand;
use Zain\LaravelDoctrine\Algolia\Services\AlgoliaSearchService;

class AlgoliaServiceProvider extends ServiceProvider
{
    public const VERSION = '0.1';

    public function boot(): void
    {
        UserAgent::addCustomUserAgent('Laravel Doctrine Algolia', self::VERSION);
        UserAgent::addCustomUserAgent('Laravel', $this->app->version());

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/algolia.php' => config_path('algolia.php'),
            ], 'config');

            $this->commands([
                SearchImportCommand::class
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/algolia.php', 'algolia');

        $this->app->bind(SearchClient::class, function () {
            return SearchClient::create(config('algolia.app'), config('algolia.secret'));
        });

        $this->app->bind(SearchService::class, function () {
            return new AlgoliaSearchService(app(Engine::class), $this->getSearchConfiguration());
        });
    }

    public function getSearchConfiguration(): array
    {
        $definition = new Configuration();
        $processor = new Processor();

        return $processor->processConfiguration($definition, [config('algolia.search')]);
    }
}
