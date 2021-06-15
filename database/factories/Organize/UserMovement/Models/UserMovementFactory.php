<?php

namespace Database\Factories\Organize\UserMovement\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Organize\UserMovement\Models\UserMovement;
use App\Constants\MovementTypeConstant;

class UserMovementFactory extends Factory
{
    private const MOVEMENT_TYPES = [
        0 => MovementTypeConstant::ENTRY,
        1 => MovementTypeConstant::OUTPUT,
    ];

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserMovement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        // TODO

        return [];
    }
}
