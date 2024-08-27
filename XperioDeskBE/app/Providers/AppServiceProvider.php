<?php

namespace App\Providers;

use App\ServiceInterfaces\AdminServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\ServiceInterfaces\SeatServiceInterface;
use App\ServiceInterfaces\UserServiceInterface;
use App\Services\UserService;
use App\Services\SeatService;
use App\Services\AdminService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SeatServiceInterface::class, SeatService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(AdminServiceInterface::class, AdminService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
