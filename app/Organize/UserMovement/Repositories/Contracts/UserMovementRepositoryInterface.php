<?php

namespace App\Organize\UserMovement\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Organize\Base\Repositories\Contracts\BaseRepositoryInterface;

interface UserMovementRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $filters
     *
     * @return Collection
     */
    public function getAllUserMovements(array $filters = []): Collection;
}
