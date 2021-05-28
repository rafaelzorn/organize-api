<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Faker\Factory;
use App\Organize\MovementCategory\Models\MovementCategory;
use App\Organize\MovementCategory\Repositories\MovementCategoryRepository;

class MovementCategoryRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $movementCategory
     */
    private $movementCategory;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->movementCategory = new MovementCategoryRepository(new MovementCategory);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_create_a_new_movement_category(): void
    {
        // Arrange
        $faker = Factory::create();
        $data  = [
            'name' => $faker->name,
        ];

        // Act
        $movementCategory = $this->movementCategory->create($data);

        // Assert
        $this->assertInstanceOf(MovementCategory::class, $movementCategory);
        $this->assertEquals($data['name'], $movementCategory->name);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_find_a_movement_category(): void
    {
        // Arrange
        $data = MovementCategory::factory()->create();

        // Act
        $movementCategory = $this->movementCategory->find($data['id']);

        // Assert
        $this->assertInstanceOf(MovementCategory::class, $movementCategory);
        $this->assertEquals($data['name'], $movementCategory->name);
    }
}
