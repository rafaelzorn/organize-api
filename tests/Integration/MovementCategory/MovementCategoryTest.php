<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use App\Constants\HttpStatusConstant;
use App\Organize\MovementCategory\Models\MovementCategory;

class MovementCategoryTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_INDEX  = '/api/v1/movements/categories';

    /**
     * @test
     *
     * @return void
     */
    public function should_return_ordered_categories(): void
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

    /**
     * @test
     *
     * @return void
     */
    public function should_not_return_categories_because_the_token_is_invalid(): void
    {
        // Arrange
        MovementCategory::factory()->create();
        $invalidToken = 'invalid.token';

        // Act
        $response = $this->call('GET', self::URL_INDEX, [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $invalidToken,
        ]);

        // Assert
        $this->assertEquals(HttpStatusConstant::UNAUTHORIZED, $response->status());
        $this->assertEquals(trans('messages.unauthorized'), $response->getContent());
    }
}
