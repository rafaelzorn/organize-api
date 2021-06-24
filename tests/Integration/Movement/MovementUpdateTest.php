<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Constants\HttpStatusConstant;
use App\Organize\UserMovement\Models\UserMovement;
use App\Organize\User\Models\User;

class MovementUpdateTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_UPDATE  = '/api/v1/movements/';

    /**
     * @test
     *
     * @return void
     */
    public function should_update_a_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $user             = $authenticateUser['user'];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        $dataToUpdate = UserMovementHelper::movementFaker();

        // Act
        $this->json('PUT', self::URL_UPDATE . $data->id, $dataToUpdate, ['HTTP_Authorization' => 'Bearer ' . $token]);

        $dataToUpdate['id'] = $data->id;

        // Assert
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => $dataToUpdate,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_update_a_movement_because_the_token_is_invalid(): void
    {
        // Arrange
        $data = UserMovement::factory()
                    ->forUser()
                    ->forMovementCategory()
                    ->create();

        $invalidToken = 'invalid.token';
        $dataToUpdate = UserMovementHelper::movementFaker();

        // Act
        $response = $this->call('PUT', self::URL_UPDATE . $data->id, $dataToUpdate, [], [], [
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
    public function should_not_update_a_movement_that_dont_exist(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser  = $this->authenticateUser();
        $token             = $authenticateUser['token'];
        $user              = $authenticateUser['user'];
        $invalidIdMovement = 2;

        UserMovement::factory()
            ->userId($user->id)
            ->forMovementCategory()
            ->create();

        $dataToUpdate = UserMovementHelper::movementFaker();

        // Act
        $this->json('PUT', self::URL_UPDATE . $invalidIdMovement, $dataToUpdate, ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::BAD_REQUEST);
        $this->seeJsonEquals([
            'code'    => HttpStatusConstant::BAD_REQUEST,
            'message' => trans('messages.error_save_movement'),
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_update_a_movement_another_users_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $otherUser        = User::factory()->create();
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        $data = UserMovement::factory()
                    ->userId($otherUser->id)
                    ->forMovementCategory()
                    ->create();

        $dataToUpdate = UserMovementHelper::movementFaker();

        // Act
        $this->json('PUT', self::URL_UPDATE . $data->id, $dataToUpdate, ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::BAD_REQUEST);
        $this->seeJsonEquals([
            'code'    => HttpStatusConstant::BAD_REQUEST,
            'message' => trans('messages.error_save_movement'),
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_update_a_movement_because_the_movement_category_is_invalid(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser        = $this->authenticateUser();
        $token                   = $authenticateUser['token'];
        $user                    = $authenticateUser['user'];
        $invalidMovementCategory = 3;

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        $dataToUpdate                         = UserMovementHelper::movementFaker();
        $dataToUpdate['movement_category_id'] = $invalidMovementCategory;

        // Act
        $this->json('PUT', self::URL_UPDATE . $data->id, $dataToUpdate, ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::BAD_REQUEST);
        $this->seeJsonEquals([
            'code'    => HttpStatusConstant::BAD_REQUEST,
            'message' => trans('messages.error_save_movement'),
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_that_fields_are_required(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $user             = $authenticateUser['user'];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        $validations = [
            'movement_category_id' => trans('validation.required'),
            'description'          => trans('validation.required'),
            'value'                => trans('validation.required'),
            'movement_date'        => trans('validation.required'),
            'movement_type'        => trans('validation.required'),
        ];

        // Act
        $response = $this->call('PUT', self::URL_UPDATE . $data->id, [], [], [], [
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
    public function should_return_validation_of_field_types(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $user             = $authenticateUser['user'];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        $dataToUpdate                         = UserMovementHelper::movementFaker();
        $dataToUpdate['movement_category_id'] = 'invalid category id';
        $dataToUpdate['description']          = rand(1, 200);
        $dataToUpdate['value']                = rand(1, 200);
        $dataToUpdate['movement_type']        = rand(1, 200);

        $validations = [
            'movement_category_id' => trans('validation.integer'),
            'description'          => trans('validation.string'),
            'value'                => trans('validation.string'),
            'movement_type'        => trans('validation.string'),
        ];

        // Act
        $response = $this->call('PUT', self::URL_UPDATE . $data->id, $dataToUpdate, [], [], [
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
    public function should_return_validation_that_date_format_is_invalid(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $user             = $authenticateUser['user'];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        $dataToUpdate                  = UserMovementHelper::movementFaker();
        $dataToUpdate['movement_date'] = date('Y-m-d H:i:s');

        $validations = [
            'movement_date' => trans('validation.date_format', ['format' => 'Y-m-d']),
        ];

        // Act
        $response = $this->call('PUT', self::URL_UPDATE . $data->id, $dataToUpdate, [], [], [
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
    public function should_return_validation_that_value_format_is_invalid(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $user             = $authenticateUser['user'];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        $dataToUpdate          = UserMovementHelper::movementFaker();
        $invalidValue          = '0100.00';
        $dataToUpdate['value'] = $invalidValue;

        $validations = [
            'value' => trans('validation.invalid_value_format'),
        ];

        // Act
        $response = $this->call('PUT', self::URL_UPDATE . $data->id, $dataToUpdate, [], [], [
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
    public function should_return_validation_that_value_is_greater_than_and_less_than(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];
        $user             = $authenticateUser['user'];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        $dataToUpdate          = UserMovementHelper::movementFaker();
        $invalidValue          = '100000000.00';
        $dataToUpdate['value'] = $invalidValue;

        $validations = [
            'value' => trans('validation.invalid_value_between'),
        ];

        // Act
        $response = $this->call('PUT', self::URL_UPDATE . $data->id, $dataToUpdate, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }
}
