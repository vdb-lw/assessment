<?php

namespace App\Controller;

use App\Service\Database\DataGetter;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DataController
{
    /**
     * @var array
     */
    protected $commonHeaders = [
        'Access-Control-Allow-Origin' => '*',
    ];

    /**
     * @param Request $request
     * @param LoggerInterface $logger
     * @param DataGetter $data
     * @return JsonResponse
     */
    public function index(Request $request, LoggerInterface $logger, DataGetter $data)
    {
        try {
            $requestParams = $request->query->all();
            if (!empty($requestParams))
            {
                $data->setQueryParameters($requestParams);
            }
            $data = $data->get();
            return new JsonResponse([
                'error_code' => 0,
                'data' => $data,
            ], 200, $this->commonHeaders);
        }
        catch (Exception $exception)
        {
            $message = sprintf('An error occurred: %s - %s:%s - %s', $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
            $logger->error($message);
            return new JsonResponse([
                'error_code' => 1,
                'error_message' => 'An error occurred and we know it. Try again later. If persist contact our support at support@domain.tld',
                'data' => [],
            ], 500, $this->commonHeaders);
        }
    }

}
