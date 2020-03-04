<?php namespace Tests;

use App\Service\Database\DataFilter;
use App\Service\Database\DataGetter;
use App\Service\Database\DataParser;
use App\Service\Database\DataReader\FileDataReader;
use App\Service\Database\GetDataReader;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class DataGetterTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSetQueryParameters()
    {
        $parameterBag = $this->makeEmpty(ContainerBagInterface::class);
        $getDataReader = $this->make(GetDataReader::class);
        $dataParser = $this->make(DataParser::class);
        $dataFilter = $this->make(DataFilter::class);

        $dataGetter = new DataGetter($parameterBag, $getDataReader, $dataParser, $dataFilter);
        $this->assertEmpty($dataGetter->setQueryParameters(['foo' => 'bar']));
    }

    public function testGetWithoutParams()
    {
        $data = ['foo' => 'bar', 'a' => 'b'];
        $dataParsed = ['foo' => 'bar'];

        $fileReader = $this->make(FileDataReader::class, ['read' => $data]);
        $parameterBag = $this->make(ContainerBag::class, ['get' => 'FILE']);
        $getDataReader = $this->make(GetDataReader::class, ['getReader' => $fileReader]);
        $dataParser = $this->make(DataParser::class, ['parse' => $dataParsed]);
        $dataFilter = $this->make(DataFilter::class, ['filterData' => Expected::never()]);

        $dataGetter = new DataGetter($parameterBag, $getDataReader, $dataParser, $dataFilter);
        $res = $dataGetter->get();

        $this->assertIsArray($res);
        $this->assertArrayHasKey('foo', $res);
        $this->assertEquals($dataParsed, $res);
    }

    public function testGetWithParams()
    {
        $data = ['foo' => 'bar', 'a' => 'b'];
        $dataParsed = ['foo' => 'bar'];
        $dataFiltered = ['Foo' => 'bar'];

        $fileReader = $this->make(FileDataReader::class, ['read' => $data]);
        $parameterBag = $this->make(ContainerBag::class, ['get' => 'FILE']);
        $getDataReader = $this->make(GetDataReader::class, ['getReader' => $fileReader]);
        $dataParser = $this->make(DataParser::class, ['parse' => $dataParsed]);
        $dataFilter = $this->make(DataFilter::class, ['filterData' => Expected::once($dataFiltered)]);

        $dataGetter = new DataGetter($parameterBag, $getDataReader, $dataParser, $dataFilter);
        $dataGetter->setQueryParameters(['p' => 'a']);
        $res = $dataGetter->get();

        $this->assertIsArray($res);
        $this->assertArrayHasKey('Foo', $res);
        $this->assertEquals($dataFiltered, $res);
    }
}