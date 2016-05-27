<?php

namespace Berkayk\OneSignal;

use Illuminate\Support\ServiceProvider;

class OneSignalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/onesignal.php';
        
        $app = $this->app;
        if (class_exists('Illuminate\Foundation\Application') && $app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes( [$configPath => config_path('onesignal.php')] );
            $this->mergeConfigFrom($configPath, 'onesignal');

        } elseif ( class_exists('Laravel\Lumen\Application', false) ) {
            $app->configure('onesignal');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('onesignal', function ($app) {
            $config = isset($app['config']['services']['onesignal']) ? $app['config']['services']['onesignal'] : null;
            if (is_null($config)) {
                $config = $app['config']['onesignal'] ?: $app['config']['onesignal::config'];
            }

            $client = new OneSignalClient($config['app_id'], $config['rest_api_key'], $config['user_auth_key']);

            return $client;
        });
    }

    public function provides() {
        return ['onesignal'];
    }


}
