<?php

namespace panopla\Translatable;

use Illuminate\Support\ServiceProvider;

class TranlatableServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/translatable.php' => config_path('translatable.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/translatable.php', 'translatable'
        );
    }
}