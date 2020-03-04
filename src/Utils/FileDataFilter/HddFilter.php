<?php

namespace App\Utils\FileDataFilter;

class HddFilter
{
    public static function filter($data, $filterConstraint)
    {
        return strpos($data['type'], $filterConstraint) !== FALSE;
    }
}
