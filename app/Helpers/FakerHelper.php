<?php

namespace App\Helpers;

class FakerHelper
{
    /**
     * @return string $value
     */
    public static function decimal(): string
    {
        $rand = rand(0, 9);

        $value = $rand . $rand;
        $value = (string) rand(1, 99999999) . '.'. $value;

        return $value;
    }
}
