<?php

namespace App\Service\Database\DataReader;

interface InterfaceDataReader
{
    /**
     * @return array
     */
    public function read() : array;

}
