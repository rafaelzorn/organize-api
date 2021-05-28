<?php

namespace Database\Factories\Organize\MovementCategory\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Organize\MovementCategory\Models\MovementCategory;

class MovementCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MovementCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
