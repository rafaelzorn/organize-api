<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Constants\HttpStatusConstant;
use App\Organize\UserMovement\Models\UserMovement;
use App\Organize\User\Models\User;
use App\Organize\MovementCategory\Models\MovementCategory;
use App\Organize\UserMovement\Resources\UserMovementResource;

class MovementIndexTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_INDEX  = '/api/v1/movements';

    /**
     * @test
     *
     * @return void
     */
    public function should_return_all_authenticated_user_movements(): void
    {
        $this->refreshApplication();

        // Arrange
        $otherUser        = User::factory()->create();
        $authenticateUser = $this->authenticateUser();
        $user             = $authenticateUser['user'];
        $token            = $authenticateUser['token'];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->count(3)
                    ->create();

        $data = UserMovementResource::collection($data);

        UserMovement::factory()
            ->userId($otherUser->id)
            ->forMovementCategory()
            ->count(2)
            ->create();

        // Act
        $this->json('GET', self::URL_INDEX, [], ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => $data,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_return_all_authenticated_user_movements_because_the_token_is_invalid(): void
    {
        // Arrange
        $invalidToken = 'invalid.token';

        // Act
        $response = $this->call('GET', self::URL_INDEX, [], [], [], [
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
    public function should_return_authenticated_user_movements_from_a_specific_category(): void
    {
        $this->refreshApplication();

        // Arrange
        $category         = MovementCategory::factory()->create();
        $otherCategory    = MovementCategory::factory()->create();
        $authenticateUser = $this->authenticateUser();
        $user             = $authenticateUser['user'];
        $token            = $authenticateUser['token'];
        $filters          = ['movement_category_id' => $category->id];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->movementCategoryId($category->id)
                    ->count(3)
                    ->create();

        $data = UserMovementResource::collection($data);

        UserMovement::factory()
            ->userId($user->id)
            ->movementCategoryId($otherCategory->id)
            ->count(2)
            ->create();

        // Act
        $this->json('GET', self::URL_INDEX, $filters, ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => $data,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_authenticated_user_movements_from_a_specific_period(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $user             = $authenticateUser['user'];
        $token            = $authenticateUser['token'];
        $filters          = [
            'movement_date_start_date' => '2021-06-14',
            'movement_date_final_date' => '2021-06-18',
        ];

        $period = UserMovement::factory()
                    ->movementDate('2021-06-15')
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->count(2)
                    ->create();

        $otherPeriod = UserMovement::factory()
                        ->movementDate('2021-06-18')
                        ->userId($user->id)
                        ->forMovementCategory()
                        ->count(2)
                        ->create();

        $data = $period->merge($otherPeriod);
        $data = UserMovementResource::collection($data);

        UserMovement::factory()
                ->movementDate('2021-06-22')
                ->userId($user->id)
                ->forMovementCategory()
                ->count(2)
                ->create();

        // Act
        $this->json('GET', self::URL_INDEX, $filters, ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => $data,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_validation_that_fields_must_have_a_value(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        $filters = [
            'movement_category_id'     => '',
            'movement_date_start_date' => '',
            'movement_date_final_date' => '',
        ];

        $validations = [
            'movement_category_id'     => trans('validation.filled'),
            'movement_date_start_date' => trans('validation.filled'),
            'movement_date_final_date' => trans('validation.filled'),
        ];

        // Act
        $response = $this->call('GET', self::URL_INDEX, $filters, [], [], [
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
    public function should_return_validation_that_field_movement_category_id_must_be_an_integer(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        $filters = [
            'movement_category_id' => 'invalid',
        ];

        $validations = [
            'movement_category_id' => trans('validation.integer'),
        ];

        // Act
        $response = $this->call('GET', self::URL_INDEX, $filters, [], [], [
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
    public function should_return_validation_that_period_dates_dont_match_format(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        $filters = [
            'movement_date_start_date' => date('Y-m-d H:i:s'),
            'movement_date_final_date' => date('Y-m-d H:i:s'),
        ];

        $validations = [
            'movement_date_start_date' => trans('validation.date_format', ['format' => 'Y-m-d']),
            'movement_date_final_date' => trans('validation.date_format', ['format' => 'Y-m-d']),
        ];

        // Act
        $response = $this->call('GET', self::URL_INDEX, $filters, [], [], [
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
    public function should_return_validation_that_the_final_date_field_is_required_when_the_start_date_is_present(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        $filters = [
            'movement_date_start_date' => date('Y-m-d'),
        ];

        $validations = [
            'movement_date_final_date' => trans('validation.required_with', ['values' => 'movement date start date']),
        ];

        // Act
        $response = $this->call('GET', self::URL_INDEX, $filters, [], [], [
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
    public function should_return_validation_that_the_start_date_field_is_required_when_the_final_date_is_present(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        $filters = [
            'movement_date_final_date' => date('Y-m-d'),
        ];

        $validations = [
            'movement_date_start_date' => trans('validation.required_with', ['values' => 'movement date final date']),
        ];

        // Act
        $response = $this->call('GET', self::URL_INDEX, $filters, [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($this->validationMessages($validations), $response->getContent());
    }
}
