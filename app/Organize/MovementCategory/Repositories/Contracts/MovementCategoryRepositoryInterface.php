<?php

namespace App\Organize\MovementCategory\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Organize\Base\Repositories\Contracts\BaseRepositoryInterface;

interface MovementCategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;
}
