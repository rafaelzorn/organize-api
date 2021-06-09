<?php

use Faker\Factory;
use App\Organize\MovementCategory\Models\MovementCategory;
use App\Constants\MovementTypeConstant;

class MovementHelper
{
    private const MOVEMENT_TYPES = [
        0 => MovementTypeConstant::ENTRY,
        1 => MovementTypeConstant::OUTPUT,
    ];

    /**
     * @return array $data
     */
    public static function movementFaker(): array
    {
        $movementCategory = MovementCategory::factory()->create();
        $faker            = Factory::create();

        $data  = [
            'movement_category_id' => $movementCategory->id,
            'description'          => $faker->sentence,
            'value'                => (string) $faker->randomFloat(2, 1, 99999999),
            'movement_date'        => $faker->date,
            'movement_type'        => self::MOVEMENT_TYPES[random_int(0, 1)],
        ];

        return $data;
    }
}
