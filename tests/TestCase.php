<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

use Illuminate\Support\Arr;
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
     * @param array $validations
     *
     * @return string $messages
     */
    public function validationMessages(array $validations): string
    {
        $messages = [];

        if (empty($validations)) {
            return $messages;
        }

        foreach ($validations as $key => $validation) {
            $attribute = str_replace('_', ' ', $key);
            $message   = [str_replace(':attribute', $attribute, trans($validation))];

            $messages[$key] = $message;
        }

        $messages = json_encode($messages);

        return $messages;
    }
}
