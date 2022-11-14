<?php

namespace App\Providers;

use Termwind\Components\Dd;
use App\Http\RequestData\AbstractData;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        $this->app->resolving(AbstractData::class, function (AbstractData $object, Application $app) {
            $object->boot($app->get('request'));
        });
    }
}
