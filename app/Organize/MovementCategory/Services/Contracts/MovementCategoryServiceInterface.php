<?php

namespace App\Organize\MovementCategory\Services\Contracts;

interface MovementCategoryServiceInterface
{
    /**
     * @return array
     */
    public function getAllMovementCategories(): array;
}
