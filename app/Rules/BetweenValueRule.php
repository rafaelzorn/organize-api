<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BetweenValueRule implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $value = floatval($value);
        $min   = 0.01;
        $max   = 99999999.99;

        if ($value < $min || $value > $max) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return trans('validation.invalid_value_between');
    }
}
