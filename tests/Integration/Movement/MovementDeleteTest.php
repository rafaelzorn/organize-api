<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use App\Organize\UserMovement\Models\UserMovement;
use App\Constants\HttpStatusConstant;
use App\Organize\User\Models\User;

class MovementDeleteTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_DELETE = '/api/v1/movements/';

    /**
     * @test
     *
     * @return void
     */
    public function should_delete_authenticated_user_movement(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $user             = $authenticateUser['user'];
        $token            = $authenticateUser['token'];

        $data = UserMovement::factory()
                    ->userId($user->id)
                    ->forMovementCategory()
                    ->create();

        // Act
        $this->json('DELETE', self::URL_DELETE . $data->id, [], ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->assertCount(0, UserMovement::all());
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => true,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_delete_another_users_movement(): void
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

        // Act
        $this->json('DELETE', self::URL_DELETE . $data->id, [], ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->assertCount(1, UserMovement::all());
        $this->seeStatusCode(HttpStatusConstant::NOT_FOUND);
        $this->seeJsonEquals([
            'code'    => HttpStatusConstant::NOT_FOUND,
            'message' => trans('messages.movement_not_found'),
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_not_delete_authenticated_user_movement_with_invalid_id(): void
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $user             = $authenticateUser['user'];
        $token            = $authenticateUser['token'];
        $otherId          = 2;

        UserMovement::factory()
            ->userId($user->id)
            ->forMovementCategory()
            ->create();

        // Act
        $this->json('DELETE', self::URL_DELETE . $otherId, [], ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->assertCount(1, UserMovement::all());
        $this->seeStatusCode(HttpStatusConstant::NOT_FOUND);
        $this->seeJsonEquals([
            'code'    => HttpStatusConstant::NOT_FOUND,
            'message' => trans('messages.movement_not_found'),
        ]);
    }
}
