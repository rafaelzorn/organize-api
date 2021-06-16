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
     * @param string $movementDate
     *
     * @return Factory
     */
    public function movementDate(string $movementDate): Factory
    {
        return $this->state(function (array $attributes) use($movementDate) {
            return [
                'movement_date' => $movementDate,
            ];
        });
    }

    /**
     * @param int $movementCategoryId
     *
     * @return Factory
     */
    public function movementCategoryId(int $movementCategoryId): Factory
    {
        return $this->state(function (array $attributes) use($movementCategoryId) {
            return [
                'movement_category_id' => $movementCategoryId,
            ];
        });
    }

    /**
     * @param int $userId
     *
     * @return Factory
     */
    public function userId(int $userId): Factory
    {
        return $this->state(function (array $attributes) use($userId) {
            return [
                'user_id' => $userId,
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
            'description'   => $this->faker->sentence,
            'value'         => (string) $this->faker->randomFloat(2, 1, 99999999),
            'movement_date' => $this->faker->date,
            'movement_type' => self::MOVEMENT_TYPES[random_int(0, 1)],
        ];
    }
}
