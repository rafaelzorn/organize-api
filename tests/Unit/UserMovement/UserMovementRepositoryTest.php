<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Faker\Factory;
use App\Constants\MovementTypeConstant;
use App\Organize\UserMovement\Models\UserMovement;
use App\Organize\UserMovement\Repositories\UserMovementRepository;
use App\Organize\MovementCategory\Models\MovementCategory;
use App\Organize\User\Models\User;

class UserMovementRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private const MOVEMENT_TYPES = [
        0 => MovementTypeConstant::ENTRY,
        1 => MovementTypeConstant::OUTPUT,
    ];

    /**
     * @var $userMovementRepository
     */
    private $userMovementRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->userMovementRepository = new UserMovementRepository(new UserMovement);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_create_a_new_user_movement(): void
    {
        // Arrange
        $user             = User::factory()->create();
        $movementCategory = MovementCategory::factory()->create();

        $faker = Factory::create();
        $data  = [
            'user_id'              => $user->id,
            'movement_category_id' => $movementCategory->id,
            'description'          => $faker->sentence,
            'value'                => $faker->randomFloat(2, 1, ),
            'movement_date'        => $faker->date,
            'movement_type'        => self::MOVEMENT_TYPES[random_int(0, 1)],
        ];

        // Act
        $userMovement = $this->userMovementRepository->create($data);

        // Assert
        $this->assertInstanceOf(UserMovement::class, $userMovement);
        $this->assertEquals($data['user_id'], $userMovement->user_id);
        $this->assertEquals($data['movement_category_id'], $userMovement->movement_category_id);
        $this->assertEquals($data['description'], $userMovement->description);
        $this->assertEquals($data['value'], $userMovement->value);
        $this->assertEquals($data['movement_date'], $userMovement->movement_date->format('Y-m-d'));
        $this->assertEquals($data['movement_type'], $userMovement->movement_type);
    }
}
