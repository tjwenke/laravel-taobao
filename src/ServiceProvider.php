<?php

namespace Tjwenke\Alidayu;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    protected $defer = true;
    public $apps = [
        'message' => Services\MessageService::class,
        'code' => Services\VerificationCodeSendService::class,
        'checker' => Services\VerificationCodeCheckService::class
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('alidayu.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'alidayu'
        );

        foreach ($this->apps as $name => $class) {
            $name = 'alidayu.' . $name;
            $this->app->singleton($name, function ($app) use ($class) {
                return new $class(config('alidayu'));
            });
        }
    }
    
    public function provides()
    {
        $apps = [];
        foreach ($this->apps as $name => $class) {
            array_push($apps, 'alidayu.' . $name);
        }
        return $apps;
    }
}
