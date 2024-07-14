<?php

namespace ProxyProviderBridge\Response;

use ProxyProviderBridge\Exception\MalformedJsonException;
use ProxyProviderBridge\Request\StoreCallDataRequest;

/**
 * Response for:
 * - {@see StoreCallDataRequest}
 */
class StoreCallDataResponse extends BaseResponse
{
    private int $callId;

    /**
     * @return int
     */
    public function getCallId(): int
    {
        return $this->callId;
    }

    /**
     * @param int $callId
     */
    public function setCallId(int $callId): void
    {
        $this->callId = $callId;
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
        $response  = parent::prefillBaseFieldsFromJsonString($json);
        $dataArray = json_decode($json, true);
        $callId    = $dataArray['id'];

        $response->setCallId($callId);

        return $response;
    }

}