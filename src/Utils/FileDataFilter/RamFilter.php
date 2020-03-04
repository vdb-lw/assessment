<?php

namespace App\Utils\FileDataFilter;

class RamFilter
{
    public static function filter($data, $filterConstraint)
    {
        $values = explode(',', $filterConstraint);
        foreach ($values AS $value)
        {
            if ($value === $data['amount'] . $data['unit_measure'])
            {
                return TRUE;
            }
        }

        return FALSE;
    }
}
