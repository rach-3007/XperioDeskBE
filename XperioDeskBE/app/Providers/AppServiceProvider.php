<?php

namespace App\Providers;

use App\ServiceInterfaces\AdminServiceInterface;
use App\Services\LayoutService;
use App\ServiceInterfaces\LayoutServiceInterface;
use App\Services\ModuleService;
use App\ServiceInterfaces\ModuleServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\ServiceInterfaces\SeatServiceInterface;
use App\ServiceInterfaces\UserServiceInterface;
use App\Services\UserService;
use App\Services\SeatService;
use App\Services\AdminService;
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
        $this->app->bind(ModuleServiceInterface::class, ModuleService::class);
        $this->app->bind(LayoutServiceInterface::class, LayoutService::class);
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
