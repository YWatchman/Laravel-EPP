<?php

namespace YWatchman\LaravelEPP\Support;

class ArrayHelper
{
    /**
     * Remove empty fields from array.
     */
    public static function filterEmpty(array &$array): array
    {
        return $array = array_filter($array, function ($value) {
            return ! (empty($value));
        });
    }
}
