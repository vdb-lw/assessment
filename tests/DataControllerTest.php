<?php namespace Tests;

use App\Controller\DataController;
use App\Service\Database\DataGetter;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use http\Exception\RuntimeException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class DataControllerTest extends Unit
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
    public function testSuccessfulResponse()
    {
        $responseContent = ['foo' => 'bar'];
        $expectedResponse = new JsonResponse([
            'error_code' => 0,
            'data' => $responseContent,
        ], 200, [ 'Access-Control-Allow-Origin' => '*', ]);
        $logger = $this->makeEmpty(LoggerInterface::class);
        $dataGetter = $this->make(DataGetter::class, [
            'get' => $responseContent,
            'setQueryParameters' => Expected::never(),
        ]);
        $parameterBag = $this->make(ParameterBag::class, ['all' => []]);
        $request = $this->make(Request::class, ['query' => $parameterBag]);

        $controller = new DataController();
        $response = $controller->index($request, $logger, $dataGetter);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getContent(), $response->getContent());
        $this->assertEquals($expectedResponse->getStatusCode(), $response->getStatusCode());
    }

    public function testSuccessfulResponseWithQueryParameters()
    {
        $responseContent = ['foo' => 'bar'];
        $expectedResponse = new JsonResponse([
            'error_code' => 0,
            'data' => $responseContent,
        ], 200, [ 'Access-Control-Allow-Origin' => '*', ]);
        $logger = $this->makeEmpty(LoggerInterface::class);
        $dataGetter = $this->make(DataGetter::class, [
            'get' => $responseContent,
            'setQueryParameters' => Expected::once(),
        ]);
        $parameterBag = $this->make(ParameterBag::class, ['all' => ['queryParam' => 'queryParam']]);
        $request = $this->make(Request::class, ['query' => $parameterBag]);

        $controller = new DataController();
        $response = $controller->index($request, $logger, $dataGetter);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getContent(), $response->getContent());
        $this->assertEquals($expectedResponse->getStatusCode(), $response->getStatusCode());
    }

    public function testErrorResponseWithQueryParameters()
    {
        $logger = $this->makeEmptyExcept(Logger::class, 'error', Expected::once());
        $this->expectException(\TypeError::class);
        $dataGetter = $this->make(DataGetter::class, [
            'get' => 'error',
            'setQueryParameters' => Expected::once(),
        ]);
        $parameterBag = $this->make(ParameterBag::class, ['all' => ['queryParam' => 'queryParam']]);
        $request = $this->make(Request::class, ['query' => $parameterBag]);

        $controller = new DataController();
        $controller->index($request, $logger, $dataGetter);
    }

    public function testErrorResponseWithoutQueryParameters()
    {
        $logger = $this->makeEmptyExcept(Logger::class, 'error', Expected::once());
        $this->expectException(\TypeError::class);
        $dataGetter = $this->make(DataGetter::class, [
            'get' => 'error',
            'setQueryParameters' => Expected::never(),
        ]);
        $parameterBag = $this->make(ParameterBag::class, ['all' => []]);
        $request = $this->make(Request::class, ['query' => $parameterBag]);

        $controller = new DataController();
        $controller->index($request, $logger, $dataGetter);
    }
}