<?php

namespace App\Service\Database;

use App\Service\Database\DataReader\InterfaceDataReader;
use RuntimeException;

class GetDataReader
{
    /**
     * @var array
     */
    protected $allowedBackend = [
        'FILE',
    ];

    /**
     * @param string $backendData
     *
     * @return InterfaceDataReader
     *
     * @throws RuntimeException
     */
    public function getReader(string $backendData) : InterfaceDataReader
    {
        if (!in_array($backendData, $this->allowedBackend))
        {
            throw new RuntimeException('You should define a correct BACKEND parameter in the env file');
        }

        $backend = ucfirst(strtolower($backendData));
        $FQN = '\App\Service\Database\DataReader\\' . $backend . 'DataReader';
        if (!class_exists($FQN))
        {
            throw new RuntimeException(sprintf('You should define a class for the %s backend parameter', $backend));
        }

        return new $FQN();
    }

}
