<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use App\Organize\Auth\Services\AuthService;
use App\Organize\Auth\Services\Contracts\AuthServiceInterface;
use App\Organize\MovementCategory\Services\MovementCategoryService;
use App\Organize\MovementCategory\Services\Contracts\MovementCategoryServiceInterface;
use App\Organize\UserMovement\Services\UserMovementService;
use App\Organize\UserMovement\Services\Contracts\UserMovementServiceInterface;

class ServiceServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(
            MovementCategoryServiceInterface::class,
            MovementCategoryService::class
        );
        $this->app->bind(
            UserMovementServiceInterface::class,
            UserMovementService::class
        );
    }
}
