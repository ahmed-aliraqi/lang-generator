<?php

namespace AhmedAliraqi\LangGenerator;

use AhmedAliraqi\LangGenerator\Console\Commands\LangGenerateCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/translation-manager.php',
            'translation-manager'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/translation-manager.php' => config_path('translation-manager.php'),
        ], 'config');

        $this->commands([
            LangGenerateCommand::class,
        ]);
    }
}
