<?php

namespace App\Organize\UserMovement\Services\Contracts;

interface UserMovementServiceInterface
{
    /**
     * @param array $filters
     *
     * @return array
     */
    public function getAllUserMovements(array $filters = []): array;

    /**
     * @param array $data
     *
     * @return array
     */
    public function store(array $data): array;
}
