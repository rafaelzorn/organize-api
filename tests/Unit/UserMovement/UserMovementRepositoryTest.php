<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Faker\Factory;
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
    public function should_create_a_new_user_movement(): void
    {
        // TODO
    }
}
