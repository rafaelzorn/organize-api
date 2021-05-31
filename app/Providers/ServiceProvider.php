<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use App\Organize\Auth\Services\AuthService;
use App\Organize\Auth\Services\Contracts\AuthServiceInterface;
use App\Organize\MovementCategory\Services\MovementCategoryService;
use App\Organize\MovementCategory\Services\Contracts\MovementCategoryServiceInterface;

class ServiceProvider extends BaseServiceProvider
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
    }
}
