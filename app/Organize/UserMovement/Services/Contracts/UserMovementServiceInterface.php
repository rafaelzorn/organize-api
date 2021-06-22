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

    /**
     * @param int $id
     *
     * @return array
     */
    public function getUserMovement(int $id): array;

    /**
     * @param int $id
     *
     * @return array
     */
    public function deleteUserMovement(int $id): array;
}
