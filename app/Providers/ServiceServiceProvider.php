<?php

namespace App\Providers;

use App\Services\BackOffice\BackOfficeService;
use App\Services\BackOffice\BackOfficeServiceInterface;
use App\Services\User\UserService;
use App\Services\User\UserServiceInterface;
use App\Services\Whatsapp\WhatsappService;
use App\Services\Whatsapp\WhatsappServiceInterface;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(WhatsappServiceInterface::class, WhatsappService::class);
        $this->app->bind(BackOfficeServiceInterface::class, BackOfficeService::class);

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
            UserServiceInterface::class,
            WhatsappServiceInterface::class,
            BackOfficeServiceInterface::class
        ];
    }

}
