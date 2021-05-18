<?php

namespace App\Organize\Auth\Services\Contracts;

interface AuthServiceInterface
{
    /**
     * @param array $credentials
     *
     * @return array
     */
    public function login(array $credentials): array;

    /**
     * @return array
     */
    public function me(): array;

    /**
     * @return array
     */
    public function logout(): array;

    /**
     * @return string array
     */
    public function refresh(): array;
}
