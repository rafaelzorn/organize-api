<?php

namespace App\Organize\UserMovement\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Organize\Base\Repositories\Contracts\BaseRepositoryInterface;
use App\Organize\UserMovement\Models\UserMovement;

interface UserMovementRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $filters
     *
     * @return Collection
     */
    public function getAllUserMovements(array $filters = []): Collection;

    /**
     * @param int $userId
     * @param int $id
     *
     * @return UserMovement
     */
    public function getUserMovement(int $userId, int $id): UserMovement;
}
