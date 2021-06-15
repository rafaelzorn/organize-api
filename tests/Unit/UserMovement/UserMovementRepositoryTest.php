<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Organize\UserMovement\Models\UserMovement;
use App\Organize\UserMovement\Repositories\UserMovementRepository;

class UserMovementRepositoryTest extends TestCase
{
    use DatabaseMigrations;

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
    public function should_return_user_movements(): void
    {
        // Arrange
        $data = [
            UserMovement::factory()->create(),
        ];

        // Act

        // Assert
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_create_a_new_user_movement(): void
    {
        // Arrange
        $data = UserMovementHelper::movementFaker(true);

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
