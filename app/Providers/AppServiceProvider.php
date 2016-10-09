<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Vinkla\Pusher\PusherServiceProvider::class);
        $this->app->register(\Laravel\Socialite\SocialiteServiceProvider::class);

        AliasLoader::getInstance([
            'Socialite' => \Laravel\Socialite\Facades\Socialite::class,
        ])->register();
    }
}
