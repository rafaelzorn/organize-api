<?php

namespace App\Organize\UserMovement\Repositories;

use App\Organize\Base\Repositories\BaseRepository;
use App\Organize\UserMovement\Repositories\Contracts\UserMovementRepositoryInterface;
use App\Organize\UserMovement\Models\UserMovement;

class UserMovementRepository extends BaseRepository implements UserMovementRepositoryInterface
{
    /**
     * @param UserMovement $userMovement
     *
     * @return void
     */
    public function __construct(UserMovement $userMovement)
    {
        $this->model = $userMovement;
    }
}
