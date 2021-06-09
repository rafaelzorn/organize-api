<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Constants\HttpStatusConstant;

class MovementStoreTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_STORE  = '/api/v1/movements';

    /**
     * @test
     *
     * @return void
     */
    public function should_create_a_new_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $data             = MovementHelper::movementFaker();

        // Act
        $this->json('POST', self::URL_STORE, $data, ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => [
                'movement_category_id' => $data['movement_category_id'],
                'description'          => $data['description'],
                'value'                => $data['value'],
                'movement_date'        => $data['movement_date'],
                'movement_type'        => $data['movement_type'],
                'id'                   => 1
            ],
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_create_a_new_movement_by_invalid_token(): void
    {
        // Arrange
        $data         = MovementHelper::movementFaker();
        $invalidToken = 'invalid.token';

        // Act
        $response = $this->call('POST', self::URL_STORE, $data, [], [], [
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
    public function should_not_create_a_new_movement_by_invalid_movement_category(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser             = $this->authenticateUser();
        $token                        = $authenticateUser['token'];
        $data                         = MovementHelper::movementFaker();
        $invalidMovementCategory      = 2;
        $data['movement_category_id'] = $invalidMovementCategory;

        // Act
        $response = $this->json('POST', self::URL_STORE, $data, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::BAD_REQUEST);
        $this->seeJsonEquals([
            'code'    => HttpStatusConstant::BAD_REQUEST,
            'message' => trans('messages.error_save_movement')
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_fields_is_required_in_create_a_new_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        $validations = [
            'movement_category_id' => 'validation.required',
            'description'          => 'validation.required',
            'value'                => 'validation.required',
            'movement_date'        => 'validation.required',
            'movement_type'        => 'validation.required',
        ];

        // Act
        $response = $this->call('POST', self::URL_STORE, [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_type_fields_create_a_new_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser             = $this->authenticateUser();
        $token                        = $authenticateUser['token'];
        $data                         = MovementHelper::movementFaker();
        $data['movement_category_id'] = 'invalid category id';
        $data['description']          = rand(1, 200);
        $data['value']                = rand(1, 200);
        $data['movement_type']        = rand(1, 200);

        $validations = [
            'movement_category_id' => 'validation.integer',
            'description'          => 'validation.string',
            'value'                => 'validation.string',
            'movement_type'        => 'validation.string',
        ];

        // Act
        $response = $this->call('POST', self::URL_STORE, $data, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_invalid_format_date_in_create_a_new_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser      = $this->authenticateUser();
        $token                 = $authenticateUser['token'];
        $data                  = MovementHelper::movementFaker();
        $data['movement_date'] = date('Y-m-d H:i:s');

        $validations = [
            'movement_date' => 'validation.date_format',
        ];

        // Act
        $response = $this->call('POST', self::URL_STORE, $data, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_invalid_value_in_create_a_new_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $data             = MovementHelper::movementFaker();
        $invalidValue     = '0100.00';
        $data['value']    = $invalidValue;

        $validations = [
            'value' => ['invalid.regex' => ['custom_message' => trans('validation.invalid_value_format')]]
        ];

        // Act
        $response = $this->call('POST', self::URL_STORE, $data, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_invalid_value_must_be_between_in_create_a_new_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $data             = MovementHelper::movementFaker();
        $invalidValue     = '100000000.00';
        $data['value']    = $invalidValue;

        $validations = [
            'value' => ['custom_message' => trans('validation.invalid_value_between')]
        ];

        // Act
        $response = $this->call('POST', self::URL_STORE, $data, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }
}
