<?php

namespace App\Organize\Auth\Services;

use App\Constants\HttpStatusConstant;
use App\Organize\Auth\Services\Contracts\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    /**
     * @param array $credentials
     *
     * @return array
     */
    public function login(array $credentials): array
    {
        if (!$token = auth()->attempt($credentials)) {
            return [
                'message' => trans('messages.unauthorized'),
                'code'    => HttpStatusConstant::UNAUTHORIZED,
            ];
        }

        return [
            'code' => HttpStatusConstant::OK,
            'data' => $this->respondWithToken($token),
        ];
    }

    /**
     * @return array
     */
    public function me(): array
    {
        return [
            'code' => HttpStatusConstant::OK,
            'data' => auth()->user(),
        ];
    }

    /**
     * @return array
     */
    public function logout(): array
    {
        auth()->logout();

        return [
            'code'    => HttpStatusConstant::OK,
            'message' => trans('messages.successfully_logged_out'),
        ];
    }

    /**
     * @return string array
     */
    public function refresh(): array
    {
        return [
            'code' => HttpStatusConstant::OK,
            'data' => [
                'token' => auth()->refresh(),
            ],
        ];
    }

    /**
     * @param string $token
     *
     * @return array
     */
    protected function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ];
    }
}
