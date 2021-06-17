<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Constants\HttpStatusConstant;
use App\Organize\UserMovement\Models\UserMovement;
use App\Organize\User\Models\User;
use App\Organize\MovementCategory\Models\MovementCategory;

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
            'movement_date_final_date' => '2021-06-18'
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
}
