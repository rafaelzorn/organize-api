<?php

namespace App\Organize\UserMovement\Services\Contracts;

interface UserMovementServiceInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function store(array $data): array;
}
