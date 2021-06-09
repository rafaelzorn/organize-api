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
     * @return array $messages
     */
    public function validationMessages(array $validations): array
    {
        $messages = [];

        if (empty($validations)) {
            return $messages;
        }

        foreach ($validations as $key => $validation) {
            $attribute     = str_replace('_', ' ', $key);
            $customMessage = $this->getCustomMessage($validation);

            if ($customMessage) {
                $message = [$customMessage];
            } else {
                $message = [str_replace(':attribute', $attribute, trans($validation))];
            }

            $messages[$key] = $message;
        }

        return $messages;
    }

    /**
     * @param mixed $validation
     *
     * @return string $customMessage
     */
    private function getCustomMessage($validation): string
    {
        if (!Arr::accessible($validation)) {
            return false;
        }

        $customMessage = '';

        if (Arr::exists($validation, 'custom_message')) {
            $customMessage = Arr::first($validation);
        } else {
            $customMessage = array_column($validation, 'custom_message');
            $customMessage = Arr::first($customMessage);
        }

        return $customMessage;
    }
}
