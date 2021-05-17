<?php

namespace App\Organize\Auth\Services;

use App\Constants\HttpStatusConstant;
use App\Organize\User\Models\User;

class AuthService
{
    /**
     * @param array $credentials
     *
     * @return array $data
     */
    public function login(array $credentials): array
    {
        if (!$token = auth()->attempt($credentials)) {
            $data = [
                'message' => trans('messages.unauthorized'),
                'code'    => HttpStatusConstant::UNAUTHORIZED,
            ];

            return $data;
        }

        $data =  [
            'code' => HttpStatusConstant::OK,
            'data' => [
                'me' => $this->respondWithToken($token),
            ],
        ];

        return $data;
    }

    /**
     * @return array $data
     */
    public function me(): array
    {
        $data = [
            'code' => HttpStatusConstant::OK,
            'data' => [
                'me' => auth()->user(),
            ],
        ];

        return $data;
    }

    /**
     * @return array $data
     */
    public function logout(): array
    {
        auth()->logout();

        $data = [
            'code'    => HttpStatusConstant::OK,
            'message' => trans('messages.successfully_logged_out'),
        ];

        return $data;
    }

    /**
     * @return string $data
     */
    public function refresh(): array
    {
        $data = [
            'code' => HttpStatusConstant::OK,
            'data' => [
                'token' => auth()->refresh(),
            ],
        ];

        return $data;
    }

    /**
     * @param string $token
     *
     * @return array $data
     */
    protected function respondWithToken(string $token): array
    {
        $data = [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ];

        return $data;
    }
}
