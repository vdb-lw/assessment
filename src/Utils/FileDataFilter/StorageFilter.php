<?php

namespace App\Utils\FileDataFilter;

class StorageFilter
{
    public static function filter($data, $filterConstraint)
    {
        return strpos($data['plain'], $filterConstraint) !== FALSE;
    }
}
