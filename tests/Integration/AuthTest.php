<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Organize\User\Models\User;
use App\Constants\HttpStatusConstant;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_LOGIN  = '/api/v1/login';
    private const TOKEN_TYPE = 'bearer';
    private const EXPIRES_IN = 3600;

    /**
     * @test
     *
     * @return void
     */
    public function should_authenticate_a_user_by_credentials(): void
    {
        // Arrange
        $password = uniqid();
        $user     = User::factory()->password($password)->create();

        // Act
        $response = $this->json('POST', self::URL_LOGIN, [
            'email'    => $user->email,
            'password' => $password,
        ]);

        // Assert
        $accessToken = auth()->getToken()->get();

        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => [
                'access_token' => $accessToken,
                'token_type'   => self::TOKEN_TYPE,
                'expires_in'   => self::EXPIRES_IN,
            ],
        ]);
    }
}
