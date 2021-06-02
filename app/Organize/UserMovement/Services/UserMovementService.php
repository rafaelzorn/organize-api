<?php

namespace App\Organize\UserMovement\Services;

use Illuminate\Support\Arr;
use Exception;
use App\Constants\HttpStatusConstant;
use App\Organize\UserMovement\Services\Contracts\UserMovementServiceInterface;
use App\Organize\UserMovement\Repositories\Contracts\UserMovementRepositoryInterface;
use App\Organize\MovementCategory\Repositories\Contracts\MovementCategoryRepositoryInterface;

class UserMovementService implements UserMovementServiceInterface
{
    /**
     * @var $userMovementRepository
     */
    private $userMovementRepository;

    /**
     * @var $movementCategoryRepository
     */
    private $movementCategoryRepository;

    /**
     * @param UserMovementRepositoryInterface $userMovementRepository
     * @param MovementCategoryRepositoryInterface $movementCategoryRepository
     *
     * @return void
     */
    public function __construct(
        UserMovementRepositoryInterface $userMovementRepository,
        MovementCategoryRepositoryInterface $movementCategoryRepository)
    {
        $this->userMovementRepository = $userMovementRepository;
        $this->movementCategoryRepository = $movementCategoryRepository;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function store(array $data): array
    {
        try {
            $this->movementCategoryRepository
                 ->findOrFail($data['movement_category_id']);

            $data = Arr::add($data, 'user_id', auth()->user()->id);

            $userMovement = $this->userMovementRepository->create($data);

            return [
                'code' => HttpStatusConstant::OK,
                'data' => $userMovement,
            ];
        } catch (Exception $e) {
            return [
                'code'    => HttpStatusConstant::INTERNAL_SERVER_ERROR,
                'message' => trans('messages.error_save_movement'),
            ];
        }
    }
}
