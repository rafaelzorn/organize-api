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
            // TODO: Aplicar validação para somente valores com 2 casas decimais
            'value'                => 'required|numeric|between:0.01,99999999.99',
            'movement_date'        => 'required|date_format:Y-m-d',
            'movement_type'        => 'required|string',
        ];
    }

    public static function messages(): array
    {
        return [
            'value.between' => trans('validation.invalid_movement_value_between'),
        ];
    }
}
