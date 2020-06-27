<?php

namespace YWatchman\LaravelEPP\Support;

class ArrayHelper
{
    /**
     * Remove empty fields from array.
     *
     * @param array $array
     *
     * @return array
     */
    public static function filterEmpty(array &$array)
    {
        return $array = array_filter($array, function ($value) {
            return !($value === null || empty($value));
        });
    }
}
