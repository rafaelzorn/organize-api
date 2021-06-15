<?php

namespace App\Helpers;

class ArrayHelper
{
    /**
     * @param array $array
     * @param string $index
     *
     * @return mixed $value
     */
    public static function checkValueArray(array $array, string $index): mixed
    {
        if (!isset($array[$index]) || $array[$index] === '') {
            return false;
        }

        return $array[$index];
    }
}
