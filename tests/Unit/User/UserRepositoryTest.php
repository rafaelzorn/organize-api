<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use App\Organize\User\Models\User;
use App\Organize\User\Repositories\UserRepository;
use Faker\Factory;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var $userRepository
     */
    private $userRepository;

     /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new UserRepository(new User);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_create_a_new_user(): void
    {
        // Arrange
        $faker = Factory::create();
        $data  = [
            'name'     => $faker->name,
            'email'    => $faker->unique()->safeEmail,
            'password' => Hash::make($faker->password),
        ];

        // Act
        $user = $this->userRepository->create($data);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['password'], $user->password);
    }

    /**
     * @test
     *
     * @return void
     */
    public function should_find_a_user(): void
    {
        // Arrange
        $data = User::factory()->create();

        // Act
        $user = $this->userRepository->find($data['id']);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['id'], $user->id);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['password'], $user->password);
    }
}
