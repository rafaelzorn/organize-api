<?php

namespace App\Organize\UserMovement\Requests;

use App\Rules\BetweenValueRule;

class UserMovementStoreRequest
{
    /**
     * @return array
     */
    public static function rules(): array
    {
        return [
            'movement_category_id' => 'required|integer',
            'description'          => 'required|string|max:255',
            'value'                => [
                'required',
                'string',
                'regex:/^[0-9]+(\.[0-9]{2})?$/',
                new BetweenValueRule,
            ],
            'movement_date'        => 'required|date_format:Y-m-d',
            'movement_type'        => 'required|string',
        ];
    }

    public static function messages(): array
    {
        return [
            'value.regex' => trans('validation.invalid_value_format'),
        ];
    }
}
