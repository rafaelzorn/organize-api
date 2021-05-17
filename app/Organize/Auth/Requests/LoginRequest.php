<?php

namespace App\Organize\Auth\Requests;

class LoginRequest
{
    /**
     * @return array
     */
    public static function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }
}
