<?php

namespace ProxyProviderBridge\Service;

use ProxyProviderBridge\Request\GetConnectionDataRequest;
use ProxyProviderBridge\Request\IsProxyReachableRequest;
use ProxyProviderBridge\Request\StoreCallDataRequest;
use ProxyProviderBridge\Request\UpdateCallDataRequest;
use ProxyProviderBridge\Response\GetConnectionDataResponse;
use GuzzleHttp\Exception\ClientException;
use ProxyProviderBridge\Request\BaseRequest;
use ProxyProviderBridge\Response\BaseResponse;
use ProxyProviderBridge\Response\IsProxyReachableResponse;
use ProxyProviderBridge\Response\StoreCallDataResponse;
use ProxyProviderBridge\Response\UpdateCallDataResponse;
use ProxyProviderBridge\Service\External\GuzzleHttpService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ProxyProviderBridge\Service\Serializer\SerializerService;
use LogicException;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Throwable;
use TypeError;

/**
 * Class BridgeService - used for performing calls toward other / external service (project)
 * @package ProxyProviderBridge
 */
class BridgeService
{
    private GuzzleHttpService $guzzleHttpService;

    private Logger $logger;

    public function __construct(
        readonly string $logFilePath,
        readonly string $loggerName,
        readonly string $baseUrl
    ) {
        $logHandler = new RotatingFileHandler($this->logFilePath, 4, Logger::DEBUG);

        $this->guzzleHttpService = new GuzzleHttpService();
        $this->logger            = new Logger($loggerName);
        $this->logger->pushHandler($logHandler);
    }

    /**
     * Get the proxy connection data
     *
     * @throws GuzzleException
     */
    public function getConnectionData(GetConnectionDataRequest $request): GetConnectionDataResponse
    {
        $response = new GetConnectionDataResponse();

        /** @var GetConnectionDataResponse $baseResponse */
        $baseResponse = $this->sendRequest($request, $response);

        return $baseResponse;
    }

    /**
     * Save the call data
     *
     * @throws GuzzleException
     */
    public function storeCallData(StoreCallDataRequest $request): StoreCallDataResponse
    {
        $response = new StoreCallDataResponse();

        /** @var StoreCallDataResponse $baseResponse */
        $baseResponse = $this->sendRequest($request, $response);

        return $baseResponse;
    }

    /**
     * Update the call data
     *
     * @throws GuzzleException
     */
    public function updateCallData(UpdateCallDataRequest $request): UpdateCallDataResponse
    {
        $response = new UpdateCallDataResponse();

        /** @var UpdateCallDataResponse $baseResponse */
        $baseResponse = $this->sendRequest($request, $response);

        return $baseResponse;
    }

    /**
     * Update the call data
     *
     * @throws GuzzleException
     */
    public function isExternalProxyReachable(IsProxyReachableRequest $request): IsProxyReachableResponse
    {
        $response = new IsProxyReachableResponse();

        /** @var IsProxyReachableResponse $baseResponse */
        $baseResponse = $this->sendRequest($request, $response);

        return $baseResponse;
    }

    /**
     * Performs base request for any type of request and returns base response
     *
     * @param BaseRequest  $baseRequest
     * @param BaseResponse $response
     *
     * @return BaseResponse
     * @throws GuzzleException
     */
    private function sendRequest(BaseRequest $baseRequest, BaseResponse $response): BaseResponse
    {
        try {
            $absoluteCalledUrl = $this->buildAbsoluteCalledUrlForRequest($baseRequest);

            $this->logCalledApiMethod($baseRequest, $absoluteCalledUrl);

            $guzzleResponse = $this->sendGuzzleRequest($baseRequest, $absoluteCalledUrl);
            $response->prefillBaseFieldsFromJsonString($guzzleResponse);

            $this->logResponse($response);
        } catch (Exception|TypeError $e) {
            $this->logThrowable($e);

            if ($e instanceof ClientException) {
                if (
                        $e->getResponse()->getStatusCode() >= 400
                    &&  $e->getResponse()->getStatusCode() < 500
                ) {
                    return $response->prefillBadRequest($e->getResponse()->getStatusCode());
                }
            }

            return $response->prefillInternalBridgeError($e->getMessage());
        }

        return $response;
    }

    /**
     * Will return the absolute url to be called by guzzle
     *
     * @param BaseRequest $request
     *
     * @return string
     */
    private function buildAbsoluteCalledUrlForRequest(BaseRequest $request): string
    {
        $outputUrl = $this->baseUrl;
        if (!str_ends_with($outputUrl, DIRECTORY_SEPARATOR)) {
            $outputUrl .= DIRECTORY_SEPARATOR;
        }

        return $outputUrl . $request->getRequestUri();
    }

    /**
     * @param Throwable $e
     */
    private function logThrowable(Throwable $e): void
    {
        $this->logger->critical("Exception was thrown", [
            "message" => $e->getMessage(),
            "code"    => $e->getCode(),
            "trace"   => $e->getTraceAsString(),
        ]);
    }

    /**
     * Will log information about current api call
     *
     * @param BaseRequest $request
     * @param string      $absoluteCalledUrl
     */
    private function logCalledApiMethod(BaseRequest $request, string $absoluteCalledUrl): void
    {
        $this->logger->info("Now calling api: ", [
            "calledMethod" => debug_backtrace()[1]['function'] ?? 'unknown', // need to use backtrace to get the correct calling method
            "baseUrl"      => $absoluteCalledUrl,
            "requestUri"   => $request->getRequestUri(),
        ]);
    }

    /**
     * Will log the response data
     *
     * @param BaseResponse $response
     */
    private function logResponse(BaseResponse $response): void
    {
        $this->logger->info("Got response from called endpoint", [
            "response" => $response->toJson(),
        ]);
    }

    /**
     * Will send request via guzzle and return the string response
     *
     * @param BaseRequest $baseRequest
     * @param string      $absoluteCalledUrl
     *
     * @return string
     * @throws GuzzleException
     */
    private function sendGuzzleRequest(BaseRequest $baseRequest, string $absoluteCalledUrl): string
    {
        $baseRequestJson  = SerializerService::getSerializer()->serialize($baseRequest, "json");
        $baseRequestArray = json_decode($baseRequestJson, true);

        switch ($baseRequest->getRequestMethod()) {
            case Request::METHOD_POST:
            {
                return $this->guzzleHttpService->sendPostRequest($absoluteCalledUrl, $baseRequestArray);
            }

            case Request::METHOD_GET:
            {
                return $this->guzzleHttpService->sendGetRequest($absoluteCalledUrl);
            }

            default:
            {
                throw new LogicException("Sending guzzle request for method type: {$baseRequest->getRequestMethod()}");
            }
        }
    }

}