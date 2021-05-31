<?php

namespace App\Organize\MovementCategory\Services;

use App\Constants\HttpStatusConstant;
use App\Organize\MovementCategory\Services\Contracts\MovementCategoryServiceInterface;
use App\Organize\MovementCategory\Repositories\Contracts\MovementCategoryRepositoryInterface;

class MovementCategoryService implements MovementCategoryServiceInterface
{
    /**
     * @var $movementCategoryRepository
     */
    private $movementCategoryRepository;

    /**
     * @param MovementCategoryRepositoryInterface $movementCategoryRepository
     *
     * @return void
     */
    public function __construct(MovementCategoryRepositoryInterface $movementCategoryRepository)
    {
        $this->movementCategoryRepository = $movementCategoryRepository;
    }

    /**
     * @return array
     */
    public function getAllMovementCategories(): array
    {
        $data = $this->movementCategoryRepository->getAllMovementCategories();

        return [
            'code' => HttpStatusConstant::OK,
            'data' => $data,
        ];
    }
}
