<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\ServiceInterfaces\SeatServiceInterface;
use App\ServiceInterfaces\UserServiceInterface;
use App\Services\SeatService;
use App\Services\UserService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SeatServiceInterface::class, SeatService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
