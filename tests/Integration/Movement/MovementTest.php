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
     * @return array $data
     */
    private function movementFaker(): array
    {
        $user             = User::factory()->create();
        $movementCategory = MovementCategory::factory()->create();
        $faker            = Factory::create();

        $data  = [
            'user_id'              => $user->id,
            'movement_category_id' => $movementCategory->id,
            'description'          => $faker->sentence,
            'value'                => (string) $faker->randomFloat(2, 1, 99999999),
            'movement_date'        => $faker->date,
            'movement_type'        => self::MOVEMENT_TYPES[random_int(0, 1)],
        ];

        return $data;
    }
}
