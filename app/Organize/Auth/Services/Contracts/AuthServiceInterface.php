<?php

namespace App\Organize\Auth\Services\Contracts;

interface AuthServiceInterface
{
    /**
     * @param array $credentials
     *
     * @return array $data
     */
    public function login(array $credentials): array;

    /**
     * @return array $data
     */
    public function me(): array;

    /**
     * @return array $data
     */
    public function logout(): array;

    /**
     * @return string $data
     */
    public function refresh(): array;
}
