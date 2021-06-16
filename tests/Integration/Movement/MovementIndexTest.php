<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Constants\HttpStatusConstant;
use App\Organize\UserMovement\Models\UserMovement;
use App\Organize\User\Models\User;

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
        $authenticateUser = $this->authenticateUser();
        $user             = $authenticateUser['user'];
        $token            = $authenticateUser['token'];
        $otherUser        = User::factory()->create();

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
}
