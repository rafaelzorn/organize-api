<?php

namespace App\Http\Requests\Auth;

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
