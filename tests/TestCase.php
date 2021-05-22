<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Organize\User\Models\User;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * @return array
     */
    public function authenticateUser(): array
    {
        $password = uniqid();
        $user     = User::factory()->password($password)->create();
        $token    = JWTAuth::fromUser($user);

        return [
            'user'  => $user,
            'token' => $token
        ];
    }

    /**
     * @return array $messages
     */
    public function validationMessages(array $validations): array
    {
        $messages = [];

        if (empty($validations)) {
            return $messages;
        }

        foreach ($validations as $key => $validation) {
            $messages[$key] = [str_replace(':attribute', $key, trans($validation))];
        }

        return $messages;
    }
}
