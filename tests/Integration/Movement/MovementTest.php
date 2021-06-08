<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use Faker\Factory;
use App\Constants\MovementTypeConstant;
use App\Organize\User\Models\User;
use App\Organize\MovementCategory\Models\MovementCategory;
use App\Constants\HttpStatusConstant;

class MovementTest extends TestCase
{
    use DatabaseMigrations;

    private const MOVEMENT_TYPES = [
        0 => MovementTypeConstant::ENTRY,
        1 => MovementTypeConstant::OUTPUT,
    ];

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
        $data             = $this->movementFaker();

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
        $data         = $this->movementFaker();
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
        $data                         = $this->movementFaker();
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

        $validationMessages = json_encode($this->validationMessages($validations));

        // Act
        $response = $this->call('POST', self::URL_STORE, [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNPROCESSABLE_ENTITY, $response->status());
        $this->assertEquals($validationMessages, $response->getContent());
    }

    /**
     * @return array $data
     */
    private function movementFaker(): array
    {
        $movementCategory = MovementCategory::factory()->create();
        $faker            = Factory::create();

        $data  = [
            'movement_category_id' => $movementCategory->id,
            'description'          => $faker->sentence,
            'value'                => (string) $faker->randomFloat(2, 1, 99999999),
            'movement_date'        => $faker->date,
            'movement_type'        => self::MOVEMENT_TYPES[random_int(0, 1)],
        ];

        return $data;
    }
}
