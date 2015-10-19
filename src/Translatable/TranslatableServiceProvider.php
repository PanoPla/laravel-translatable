<?php

namespace panopla\Translatable;

use Illuminate\Support\ServiceProvider;

class TranslatableServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/translatable.php' => config_path('translatable.php'), 'config'
        ]);

        $this->publishes([
            __DIR__ . '/../migrations/create_language_table' => database_path('/migrations/' . date('Y_m_d_His_')), 'migrations'
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