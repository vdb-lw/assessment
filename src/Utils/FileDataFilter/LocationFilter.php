<?php

namespace App\Utils\FileDataFilter;

class LocationFilter
{
    public static function filter($data, $filterConstraint)
    {
        return $data === $filterConstraint;
    }
}
