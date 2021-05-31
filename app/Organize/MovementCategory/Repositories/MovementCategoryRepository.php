<?php

namespace App\Organize\MovementCategory\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Organize\Base\Repositories\BaseRepository;
use App\Organize\MovementCategory\Repositories\Contracts\MovementCategoryRepositoryInterface;
use App\Organize\MovementCategory\Models\MovementCategory;

class MovementCategoryRepository extends BaseRepository implements MovementCategoryRepositoryInterface
{
    /**
     * @param MovementCategory $movementCategory
     *
     * @return void
     */
    public function __construct(MovementCategory $movementCategory)
    {
        $this->model = $movementCategory;
    }

    /**
     * @return Collection
     */
    public function getAllMovementCategories(): Collection
    {
        return $this->model->orderByName()->get();
    }
}
