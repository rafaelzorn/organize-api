<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Organize\User\Models\User;
use App\Constants\HttpStatusConstant;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_LOGIN   = '/api/v1/login';
    private const URL_ME      = '/api/v1/me';
    private const URL_LOGOUT  = '/api/v1/logout';
    private const URL_REFRESH = '/api/v1/refresh';

    private const TOKEN_TYPE  = 'bearer';
    private const EXPIRES_IN  = 3600;

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
        $data     = [
            'email'    => $user->email,
            'password' => $password,
        ];

        // Act
        $this->json('POST', self::URL_LOGIN, $data);

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

    /**
     * @test
     *
     * @return void
     */
    public function should_return_unauthorized_invalid_credentials(): void
    {
        // Arrange
        User::factory()->create();

        $data = [
            'email'    => 'invalid@invalid.com.br',
            'password' => 'invalid',
        ];

        // Act
        $this->json('POST', self::URL_LOGIN, $data);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::UNAUTHORIZED);
        $this->seeJsonEquals([
            'code'    => HttpStatusConstant::UNAUTHORIZED,
            'message' => trans('messages.unauthorized'),
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_email_and_password_is_required(): void
    {
        // Arrange
        $validations = [
            'email'    => 'validation.required',
            'password' => 'validation.required',
        ];

        $validationMessages = json_encode($this->validationMessages($validations));

        User::factory()->create();

        // Act
        $response = $this->call('POST', self::URL_LOGIN);

        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($validationMessages, $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_email_invalid(): void
    {
        // Arrange
        $password = uniqid();
        User::factory()->password($password)->create();

        $validations = ['email' => 'validation.email'];
        $data        = [
            'email'    => 'invalidinvalid.com.br',
            'password' => $password,
        ];

        $validationMessages = json_encode($this->validationMessages($validations));

        // Act
        $response = $this->call('POST', self::URL_LOGIN, $data);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($validationMessages, $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_a_authenticated_user(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $user             = $authenticateUser['user'];
        $token            = $authenticateUser['token'];

        // Act
        $this->json('GET', self::URL_ME, [], ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => [
                'name'  => $user['name'],
                'email' => $user['email'],
            ],
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_return_a_user_authenticating_by_invalid_token(): void
    {
        $this->refreshApplication();

        // Arrange
        User::factory()->create();
        $invalidToken = 'invalid.token';

        // Act
        $response = $this->call('GET', self::URL_ME, [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $invalidToken,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNAUTHORIZED, $response->status());
        $this->assertEquals(trans('messages.unauthorized'), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_logout_a_authenticated_user(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        // Act
        $this->json('GET', self::URL_LOGOUT, [], ['Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code'    => HttpStatusConstant::OK,
            'message' => trans('messages.successfully_logged_out'),
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_logout_a_user_authenticating_by_invalid_token(): void
    {
        $this->refreshApplication();

        // Arrange
        User::factory()->create();
        $invalidToken = 'invalid.token';

        // Act
        $response = $this->call('GET', self::URL_LOGOUT, [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $invalidToken
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNAUTHORIZED, $response->status());
        $this->assertEquals(trans('messages.unauthorized'), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_refresh_a_valid_token(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        // Act
        $response = $this->json('GET', self::URL_REFRESH, [], ['Authorization' => 'Bearer ' . $token]);

        // Assert
        $refreshedToken = $response->response['data']['token'];

        auth()->setToken($refreshedToken);

        $token = auth()->getToken()->get();

        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => [
                'token' => $token,
            ]
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_refresh_token_by_invalid_token(): void
    {
        $this->refreshApplication();

        // Arrange
        User::factory()->create();
        $invalidToken = 'invalid.token';

        // Act
        $response = $this->call('GET', self::URL_REFRESH, [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $invalidToken,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNAUTHORIZED, $response->status());
        $this->assertEquals(trans('messages.unauthorized'), $response->getContent());
    }
}
