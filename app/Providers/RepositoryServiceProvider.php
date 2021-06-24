<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Organize\Base\Repositories\BaseRepository;
use App\Organize\Base\Repositories\Contracts\BaseRepositoryInterface;
use App\Organize\User\Repositories\UserRepository;
use App\Organize\User\Repositories\Contracts\UserRepositoryInterface;
use App\Organize\MovementCategory\Repositories\MovementCategoryRepository;
use App\Organize\MovementCategory\Repositories\Contracts\MovementCategoryRepositoryInterface;
use App\Organize\UserMovement\Repositories\UserMovementRepository;
use App\Organize\UserMovement\Repositories\Contracts\UserMovementRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(
            MovementCategoryRepositoryInterface::class,
            MovementCategoryRepository::class
        );
        $this->app->bind(
            UserMovementRepositoryInterface::class,
            UserMovementRepository::class
        );
    }
}
