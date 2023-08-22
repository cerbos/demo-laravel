<?php

namespace App\Providers;

use Cerbos\Sdk\Builder\CerbosClientBuilder;
use Cerbos\Sdk\CerbosClient;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CerbosServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CerbosClient::class, function (Application $app) {
            return CerbosClientBuilder::newInstance(env('CERBOS_HOST') .":". env('CERBOS_PORT'))
                ->withPlaintext(true)
                ->build();
        });
    }

    public function provides(): array
    {
        return [CerbosClient::class];
    }
}
