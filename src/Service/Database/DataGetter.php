<?php

namespace App\Service\Database;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class DataGetter
{
    protected $configParams;
    protected $getReader;
    protected $parser;
    protected $dataFilter;

    protected $queryParameters = [];

    /**
     * GetData constructor.
     * @param ContainerBagInterface $params
     * @param GetDataReader $reader
     * @param DataParser $parser
     * @param DataFilter $dataFilter
     */
    public function __construct(ContainerBagInterface $params, GetDataReader $reader, DataParser $parser, DataFilter $dataFilter)
    {
        $this->configParams = $params;
        $this->getReader = $reader;
        $this->parser = $parser;
        $this->dataFilter = $dataFilter;
    }

    /**
     * @return array
     */
    public function get() : array
    {
        $reader = $this->getReader->getReader($this->configParams->get('app.backend_data'));
        $data = $reader->read();
        $data = $this->parser->parse($data);
        if (!empty($this->queryParameters))
        {
            $data = $this->dataFilter->filterData($this->queryParameters, $data);
        }
        return $data;
    }

    /**
     * @param array $parameters
     */
    public function setQueryParameters(array $parameters)
    {
        $this->queryParameters = $parameters;
    }

}
