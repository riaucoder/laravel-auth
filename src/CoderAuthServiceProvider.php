<?php

namespace Riaucoder\LaravelAuth;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Riaucoder\LaravelAuth\Command\InstallCommand;

class CoderAuthServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Command\InstallCommand::class
        ]);
    }

    public function provides()
    {
        return [InstallCommand::class];
    }
}
