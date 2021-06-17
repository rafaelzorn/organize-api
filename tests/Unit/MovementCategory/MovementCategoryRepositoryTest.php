<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Faker\Factory;
use App\Organize\MovementCategory\Models\MovementCategory;
use App\Organize\MovementCategory\Repositories\MovementCategoryRepository;

class MovementCategoryRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $movementCategoryRepository
     */
    private $movementCategoryRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->movementCategoryRepository = new MovementCategoryRepository(new MovementCategory);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_create_a_new_category(): void
    {
        // Arrange
        $faker = Factory::create();
        $data  = [
            'name' => $faker->name,
        ];

        // Act
        $movementCategory = $this->movementCategoryRepository->create($data);

        // Assert
        $this->assertInstanceOf(MovementCategory::class, $movementCategory);
        $this->assertEquals($data['name'], $movementCategory->name);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_find_a_category(): void
    {
        // Arrange
        $data = MovementCategory::factory()->create();

        // Act
        $movementCategory = $this->movementCategoryRepository->find($data['id']);

        // Assert
        $this->assertInstanceOf(MovementCategory::class, $movementCategory);
        $this->assertEquals($data['name'], $movementCategory->name);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_return_ordered_categories(): void
    {
        // Arrange
        $data = [
            2 => MovementCategory::factory()->name('Viagem')->create(),
            0 => MovementCategory::factory()->name('Casa')->create(),
            1 => MovementCategory::factory()->name('Outros')->create(),
        ];

        // Act
        $movementCategories = $this->movementCategoryRepository->getAllMovementCategories();

        // Assert
        $this->assertCount(3, $movementCategories);

        foreach ($movementCategories as $key => $movementCategory) {
            $this->assertEquals($movementCategory['name'], $data[$key]['name']);
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_find_a_category_and_dont_failed(): void
    {
        // Arrange
        $data = MovementCategory::factory()->create();

        // Act
        $movementCategory = $this->movementCategoryRepository->findOrFail($data['id']);

        // Assert
        $this->assertInstanceOf(MovementCategory::class, $movementCategory);
        $this->assertEquals($data['name'], $movementCategory->name);
    }
}
