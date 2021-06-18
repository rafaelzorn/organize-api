<?php

namespace App\Organize\UserMovement\Requests;

use App\Rules\BetweenValueRule;

class UserMovementIndexRequest
{
    /**
     * @return array
     */
    public static function rules(): array
    {
        return [
            'movement_category_id'     => 'filled|integer',
            'movement_date_start_date' => 'filled|required_with:movement_date_final_date|date_format:Y-m-d',
            'movement_date_final_date' => 'filled|required_with:movement_date_start_date|date_format:Y-m-d',
        ];
    }
}
