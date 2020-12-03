<?php

namespace InsitesConsulting\DecipherApi;

use Illuminate\Support\ServiceProvider;
use InsitesConsulting\DecipherApi\Factories\Client;

class DecipherServiceProvider extends ServiceProvider
{
    /**
     * Defer loading of the provider until required.
     *
     * @var bool
     */
    protected $defer = true;


    /**
     * Bootstrap any application services.
     *
     * Allow publishing of the config file.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/decipher.php' => config_path('decipher.php'),
        ], 'decipher');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/decipher.php', 'decipher');

        $this->app->singleton(Decipher::class, function ($app) {
            $config = $app->make('config');

            return new Decipher(
                new Client(
                    $config->get('decipher.api_uri'),
                    $config->get('decipher.api_key')
                )
            );
        });

        $this->app->alias(Decipher::class, 'decipher');
    }

    public function provides()
    {
        return [
            'decipher',
            Decipher::class,
        ];
    }
}
