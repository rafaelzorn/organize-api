<?php

namespace Database\Factories\Organize\User\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Organize\User\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * @return Factory
     */
    public function password($password): Factory
    {
        return $this->state(function (array $attributes) use($password) {
            return [
                'password' => Hash::make($password),
            ];
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'     => $this->faker->name,
            'email'    => $this->faker->unique()->safeEmail,
            'password' => Hash::make($this->faker->password),
        ];
    }
}
