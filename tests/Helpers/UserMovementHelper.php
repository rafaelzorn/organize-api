<?php

use Faker\Factory;
use App\Helpers\FakerHelper;
use App\Organize\MovementCategory\Models\MovementCategory;
use App\Constants\MovementTypeConstant;
use App\Organize\User\Models\User;
use App\Organize\UserMovement\Models\UserMovement;

class UserMovementHelper
{
    private const MOVEMENT_TYPES = [
        0 => MovementTypeConstant::ENTRY,
        1 => MovementTypeConstant::OUTPUT,
    ];

    /**
     * @param bool $withUserId
     *
     * @return UserMovement $userMovement
     */
    public static function movementFaker(bool $withUserId = false): UserMovement
    {
        $movementCategory = MovementCategory::factory()->create();
        $faker            = Factory::create();

        $userMovement = new UserMovement();

        $userMovement->movement_category_id = $movementCategory->id;
        $userMovement->description          = $faker->sentence;
        $userMovement->value                = FakerHelper::decimal();
        $userMovement->movement_date        = $faker->date;
        $userMovement->movement_type        = self::MOVEMENT_TYPES[random_int(0, 1)];

        if ($withUserId) {
            $user                  = User::factory()->create();
            $userMovement->user_id = $user->id;
        }

        return $userMovement;
    }
}
