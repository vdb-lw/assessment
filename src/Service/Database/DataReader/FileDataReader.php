<?php

namespace App\Service\Database\DataReader;

use RuntimeException;

class FileDataReader implements InterfaceDataReader
{

    /**
     * @return array
     *
     * @throws RuntimeException
     */
    public function read() : array
    {
        if (!file_exists('../data/database.csv'))
        {
            throw new RuntimeException('Cannot read the database file');
        }

        return array_map('str_getcsv', file('../data/database.csv'));
    }

}
