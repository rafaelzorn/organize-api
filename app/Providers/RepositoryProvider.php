<?php

namespace App\Providers;

use App\Organize\Base\Repositories\BaseRepository;
use App\Organize\Base\Repositories\Contracts\BaseRepositoryInterface;
use App\Organize\User\Repositories\UserRepository;
use App\Organize\User\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
