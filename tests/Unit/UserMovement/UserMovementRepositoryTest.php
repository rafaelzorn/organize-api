<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use App\Organize\UserMovement\Models\UserMovement;
use App\Organize\UserMovement\Repositories\UserMovementRepository;
use App\Organize\User\Models\User;
use App\Organize\MovementCategory\Models\MovementCategory;

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
    public function should_return_all_movements_from_a_specific_user(): void
    {
        // Arrange
        $user      = User::factory()->create();
        $otherUser = User::factory()->create();
        $filters   = ['user_id' => $user->id];

        $data = UserMovement::factory()
            ->userId($user->id)
            ->forMovementCategory()
            ->count(3)
            ->create();

        UserMovement::factory()
            ->userId($otherUser->id)
            ->forMovementCategory()
            ->count(2)
            ->create();

        // Act
        $userMovements = $this->userMovementRepository->getAllUserMovements($filters);

        // Assert
        $this->assertCount(3, $userMovements);

        foreach ($userMovements as $key => $userMovement) {
            $this->assertEquals($data[$key]->toArray(), $userMovement->toArray());
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_all_movements_from_a_specific_category(): void
    {
        // Arrange
        $category      = MovementCategory::factory()->create();
        $otherCategory = MovementCategory::factory()->create();
        $filters       = ['movement_category_id' => $category->id];

        $data = UserMovement::factory()
                    ->movementCategoryId($category->id)
                    ->forUser()
                    ->count(3)
                    ->create();

        UserMovement::factory()
            ->movementCategoryId($otherCategory->id)
            ->forUser()
            ->count(2)
            ->create();

        // Act
        $userMovements = $this->userMovementRepository->getAllUserMovements($filters);

        // Assert
        $this->assertCount(3, $userMovements);

        foreach ($userMovements as $key => $userMovement) {
            $this->assertEquals($data[$key]->toArray(), $userMovement->toArray());
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_all_movements_from_a_specific_period(): void
    {
        // Arrange
        $filters = [
            'movement_date_start_date' => '2021-06-14',
            'movement_date_final_date' => '2021-06-18',
        ];

        $period = UserMovement::factory()
                    ->movementDate('2021-06-15')
                    ->forUser()
                    ->forMovementCategory()
                    ->count(2)
                    ->create();

        $otherPeriod = UserMovement::factory()
                        ->movementDate('2021-06-18')
                        ->forUser()
                        ->forMovementCategory()
                        ->count(2)
                        ->create();

        $data = $period->merge($otherPeriod);

        UserMovement::factory()
                ->movementDate('2021-06-22')
                ->forUser()
                ->forMovementCategory()
                ->count(2)
                ->create();

        // Act
        $userMovements = $this->userMovementRepository->getAllUserMovements($filters);

        // Assert
        $this->assertCount(4, $userMovements);

        foreach ($userMovements as $key => $userMovement) {
            $this->assertEquals($data[$key]->toArray(), $userMovement->toArray());
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_all_movements(): void
    {
        // Arrange
        $data = UserMovement::factory()
                    ->forUser()
                    ->forMovementCategory()
                    ->count(4)
                    ->create();

        // Act
        $userMovements = $this->userMovementRepository->getAllUserMovements();

        // Assert
        $this->assertCount(4, $userMovements);

        foreach ($userMovements as $key => $userMovement) {
            $this->assertEquals($data[$key]->toArray(), $userMovement->toArray());
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_create_a_new_movement(): void
    {
        // Arrange
        $data = UserMovementHelper::movementFaker(true);

        // Act
        $userMovement = $this->userMovementRepository->create($data);

        // Assert
        $this->assertInstanceOf(UserMovement::class, $userMovement);

        $data         = Arr::except($data, ['user_id']);
        $userMovement = Arr::except($userMovement->toArray(), ['id', 'user_id']);

        $this->assertEquals($data, $userMovement);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_find_a_movement_and_dont_failed(): void
    {
        // Arrange
        $user = User::factory()->create();
        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        // Act
        $userMovement = $this->userMovementRepository
                             ->getUserMovement($user->id, $data['id']);

        // Assert
        $this->assertInstanceOf(UserMovement::class, $userMovement);
        $this->assertEquals($data->toArray(), $userMovement->toArray());
    }
}
