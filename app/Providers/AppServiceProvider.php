<?php

namespace App\Providers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $isDev = ! app()->isProduction();
        Model::preventLazyLoading($isDev);
        Model::preventSilentlyDiscardingAttributes($isDev);
        // We intentionally do not enable preventAccessingMissingAttributes
        // because MongoDB is schema-less and documents may naturally lack fields.


    }
}
