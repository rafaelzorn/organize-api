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
            return [
                'message' => trans('messages.unauthorized'),
                'code'    => HttpStatusConstant::UNAUTHORIZED
            ];
        }

        $data = $this->respondWithToken($token);

        return $data;
    }

    /**
     * @return User
     */
    public function me(): User
    {
        return auth()->user();
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        auth()->logout();
    }

    /**
     * @return string $token
     */
    public function refresh(): string
    {
        $token = auth()->refresh();

        return $token;
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
