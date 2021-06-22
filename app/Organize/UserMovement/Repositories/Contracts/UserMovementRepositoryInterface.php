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
    public function getAllMovements(array $filters = []): Collection;

    /**
     * @param int $userId
     * @param int $id
     *
     * @return UserMovement
     */
    public function getUserMovement(int $userId, int $id): ?UserMovement;

    /**
     * @param int $userId
     * @param int $id
     *
     * @return bool
     */
    public function deleteUserMovement(int $userId, int $id): bool;

    /**
     * @param int $userId
     * @param int $id
     * @param array $data
     *
     * @return UserMovement
     */
    public function updateUserMovement(int $userId, int $id, array $data): UserMovement;
}
