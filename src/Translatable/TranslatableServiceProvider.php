<?php

namespace panopla\Translatable;

use Illuminate\Support\ServiceProvider;

class TranslatableServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/translatable.php' =>
                config_path('translatable.php')
            , 'config'
        ]);

        if (!class_exists('CreateLanguageTable')) {

            $this->publishes([
                __DIR__ . '/../database/migrations/create_language_table.php' =>
                    database_path('migrations/' . date('Y_m_d_His_') . 'create_language_table.php')
                , 'migrations'
            ]);
        }
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

        $this->app->singleton('translatable', function ($app) {
            return new Translatable;
        });
    }
}