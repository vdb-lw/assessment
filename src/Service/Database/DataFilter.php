<?php


namespace App\Service\Database;


class DataFilter
{
    protected $allowedFilters = [
        'Hdd', 'Ram', 'Location', 'Storage',
    ];
    protected $filters = [
        'Hdd' => [ 'Hdd', 'Storage', ],
        'Ram' => [ 'Ram', ],
        'Location' => [ 'Location', ],
    ];

    /**
     * @param array $queryParameters
     * @param array $data
     *
     * @return array
     */
    public function filterData(array $queryParameters, array $data) :array
    {
        $queryParameters = $this->paramsPolishing($queryParameters);
        foreach ($data as $key => $datum)
        {
            foreach ($datum AS $k => $d)
            {
                if (!isset($queryParameters[$k]) && !isset($this->filters[$k]))
                {
                    continue;
                }

                foreach ($this->filters[$k] as $filter)
                {
                    if (!isset($queryParameters[$filter]))
                    {
                        continue;
                    }
                    $FQN = '\App\Utils\FileDataFilter\\' . $filter . 'Filter';

                    if (!class_exists($FQN))
                    {
                        continue;
                    }
                    $result = $FQN::filter($d, $queryParameters[$filter]);
                    if ($result === FALSE)
                    {
                        unset($data[$key]);
                        continue;
                    }
                }
            }
        }
        return array_values($data);
    }

    protected function paramsPolishing($params)
    {
        // Remove empty params.
        $params = array_filter($params);
        $params = array_filter($params, function ($item) {
            return $item !== 'null' && $item !== 0;
        });
        // Remove not allowed params.
        $params = array_filter($params, function($key) {
            return in_array($key, $this->allowedFilters);
        }, ARRAY_FILTER_USE_KEY);
        $keys = array_keys($params);
        array_walk($keys, function(&$key) {
            $key = ucfirst(strtolower($key));
        });
        $params = array_combine($keys, array_values($params));
        return $params;
    }

}
