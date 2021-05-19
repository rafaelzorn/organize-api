<?php

namespace App\Organize\User\Repositories;

use App\Organize\Base\Repositories\BaseRepository;
use App\Organize\User\Repositories\Contracts\UserRepositoryInterface;
use App\Organize\User\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @param User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }
}
