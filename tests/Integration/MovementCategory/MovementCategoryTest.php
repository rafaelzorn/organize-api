<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use App\Constants\HttpStatusConstant;
use App\Organize\MovementCategory\Models\MovementCategory;

class MovementCategoryTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_INDEX  = '/api/v1/movement/categories';

    /**
     * @test
     *
     * @return void
     */
    public function should_return_ordered_movement_categories()
    {
        $this->refreshApplication();

        // Arrange
        $authenticateUser = $this->authenticateUser();
        $token            = $authenticateUser['token'];

        $data = [
            2 => MovementCategory::factory()->name('Viagem')->create(),
            0 => MovementCategory::factory()->name('Casa')->create(),
            1 => MovementCategory::factory()->name('Outros')->create(),
        ];

        $sorted = Arr::sort($data);

        // Act
        $this->json('GET', self::URL_INDEX, [], ['HTTP_Authorization' => 'Bearer ' . $token]);

        // Assert
        $this->seeStatusCode(HttpStatusConstant::OK);
        $this->seeJsonEquals([
            'code' => HttpStatusConstant::OK,
            'data' => $sorted,
        ]);
    }
}
