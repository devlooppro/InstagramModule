<?php

namespace App\Instagram\Providers;

use Illuminate\Support\ServiceProvider;

class InstagramServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RepositoryServiceProvider::class);

        $this->mergeConfigFrom(app_path('Instagram/config/instagram.php'), 'instagram');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
