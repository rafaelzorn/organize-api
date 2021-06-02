<?php

namespace App\Organize\UserMovement\Requests;

class UserMovementStoreRequest
{
    /**
     * @return array
     */
    public static function rules(): array
    {
        return [
            'movement_category_id' => 'required|integer',
            'description'          => 'required',
            'value'                => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'movement_date'        => 'required|date_format:Y-m-d',
            'movement_type'        => 'required|string',
        ];
    }

    public static function messages(): array
    {
        return [
            'value.regex' => 'Invalid value'
        ];
    }
}
