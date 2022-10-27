<?php

namespace App\Providers;

use App\Repositories\Eloquent\BackOfficeRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\WhatsappRepository;
use App\Repositories\Interfaces\BackOfficeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WhatsappRepositoryInterface;
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WhatsappRepositoryInterface::class, WhatsappRepository::class);
        $this->app->bind(BackOfficeRepositoryInterface::class, BackOfficeRepository::class);

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

    public function provides()
    {
        return [
            UserRepositoryInterface::class,
            WhatsappRepositoryInterface::class,
            BackOfficeRepositoryInterface::class
        ];
    }
}
