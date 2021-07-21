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
    public function getAllMovements(array $filters = []): Collection
    {
        $query = $this->model->query();

        $query = $this->filter($query, $filters);

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
     * @param int $userId
     * @param int $id
     *
     * @return bool
     */
    public function deleteUserMovement(int $userId, int $id): bool
    {
        return $this->model
                    ->whereByUserId($userId)
                    ->whereById($id)
                    ->firstOrFail()
                    ->delete();
    }

    /**
     * @param int $userId
     * @param int $id
     * @param array $data
     *
     * @return UserMovement
     */
    public function updateUserMovement(int $userId, int $id, array $data): UserMovement
    {
        $userMovement = $this->model
                             ->whereByUserId($userId)
                             ->whereById($id)
                             ->firstOrFail();

        $userMovement->update($data);

        return $userMovement;
    }

    /**
     * @param Builder $query
     * @param array $filters
     *
     * @return Builder
     */
    private function filter(Builder $query, array $filters): Builder
    {
        $userId                = ArrayHelper::checkValueArray($filters, 'user_id');
        $movementCategoryId    = ArrayHelper::checkValueArray($filters, 'movement_category_id');
        $movementDateStartDate = ArrayHelper::checkValueArray($filters, 'movement_date_start_date');
        $movementDateFinalDate = ArrayHelper::checkValueArray($filters, 'movement_date_final_date');

        if ($userId) {
            $query = $query->whereByUserId($userId);
        }

        if ($movementCategoryId) {
            $query = $query->whereByMovementCategoryId($movementCategoryId);
        }

        if ($movementDateStartDate && $movementDateFinalDate) {
            $query = $query->whereBetweenMovementDatePeriod($movementDateStartDate, $movementDateFinalDate);
        }

        return $query;
    }
}
