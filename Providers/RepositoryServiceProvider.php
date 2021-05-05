<?php

namespace App\Instagram\Providers;

use App\Instagram\Repositories\Contracts\InstagramRepositoryInterface;
use App\Instagram\Repositories\InstagramRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InstagramRepositoryInterface::class, InstagramRepository::class);
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
