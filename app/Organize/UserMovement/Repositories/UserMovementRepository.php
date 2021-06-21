<?php

namespace App\Organize\UserMovement\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Organize\Base\Repositories\BaseRepository;
use App\Organize\UserMovement\Repositories\Contracts\UserMovementRepositoryInterface;
use App\Organize\UserMovement\Models\UserMovement;
use App\Helpers\ArrayHelper;

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

    /**
     * @param array $filters
     *
     * @return Collection
     */
    public function getAllUserMovements(array $filters = []): Collection
    {
        $query = $this->model->query();

        $query = $this->filterByUserId($query, $filters);
        $query = $this->filterByMovementCategoryId($query, $filters);
        $query = $this->filterByMovementDatePeriod($query, $filters);

        return $query->get();
    }

    /**
     * @param int $userId
     * @param int $id
     *
     * @return UserMovement
     */
    public function getUserMovement(int $userId, int $id): ?UserMovement
    {
        return $this->model
                    ->whereByUserId($userId)
                    ->whereById($id)
                    ->firstOrFail();
    }

    /**
     * @param Builder $query
     * @param array $filters
     *
     * @return Builder
     */
    private function filterByUserId(Builder $query, array $filters): Builder
    {
        $userId = ArrayHelper::checkValueArray($filters, 'user_id');

        if ($userId) {
            $query = $query->whereByUserId($userId);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $filters
     *
     * @return Builder
     */
    private function filterByMovementCategoryId(Builder $query, array $filters): Builder
    {
        $movementCategoryId = ArrayHelper::checkValueArray($filters, 'movement_category_id');

        if ($movementCategoryId) {
            $query = $query->whereByMovementCategoryId($movementCategoryId);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $filters
     *
     * @return Builder
     */
    private function filterByMovementDatePeriod($query, $filters): Builder
    {
        $movementDateStartDate = ArrayHelper::checkValueArray($filters, 'movement_date_start_date');
        $movementDateFinalDate = ArrayHelper::checkValueArray($filters, 'movement_date_final_date');

        if ($movementDateStartDate && $movementDateFinalDate) {
            $query = $query->whereBetweenMovementDatePeriod($movementDateStartDate, $movementDateFinalDate);
        }

        return $query;
    }
}
