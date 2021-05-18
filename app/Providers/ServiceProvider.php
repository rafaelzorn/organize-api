<?php

namespace App\Providers;

use App\Organize\Auth\Services\AuthService;
use App\Organize\Auth\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }
}
