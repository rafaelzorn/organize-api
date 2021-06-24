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
    public function createUserMovement(array $data): array;

    /**
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function updateUserMovement(int $id, array $data): array;

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
