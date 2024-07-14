<?php

namespace ProxyProviderBridge\Response;

use ProxyProviderBridge\Dto\ConnectionDataDto;
use ProxyProviderBridge\Exception\MalformedJsonException;
use ProxyProviderBridge\Request\GetConnectionDataRequest;
use ProxyProviderBridge\Service\Serializer\SerializerService;

/**
 * Response for:
 * - {@see GetConnectionDataRequest}
 */
class GetConnectionDataResponse extends BaseResponse
{
    private ConnectionDataDto $connectionData;

    /**
     * @return ConnectionDataDto
     */
    public function getConnectionData(): ConnectionDataDto
    {
        return $this->connectionData;
    }

    /**
     * @param ConnectionDataDto $connectionData
     */
    public function setConnectionData(ConnectionDataDto $connectionData): void
    {
        $this->connectionData = $connectionData;
    }

    /**
     * {@inheritDoc}
     * @param string $json
     *
     * @return $this
     * @throws MalformedJsonException
     */
    public function prefillBaseFieldsFromJsonString(string $json): static
    {
        $response          = parent::prefillBaseFieldsFromJsonString($json);
        $connectionDataDto = SerializerService::getSerializer()->deserialize($json, ConnectionDataDto::class, 'json');

        $response->setConnectionData($connectionDataDto);

        return $response;
    }

}