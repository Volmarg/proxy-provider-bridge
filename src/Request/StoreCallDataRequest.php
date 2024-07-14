<?php

namespace ProxyProviderBridge\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides the proxy connection data
 */
class StoreCallDataRequest extends BaseRequest
{
    private const URI = "store-call-data";

    private string $url;
    private string $proxyIp;
    private int $proxyPort;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getProxyIp(): string
    {
        return $this->proxyIp;
    }

    /**
     * @param string $proxyIp
     */
    public function setProxyIp(string $proxyIp): void
    {
        $this->proxyIp = $proxyIp;
    }

    /**
     * @return int
     */
    public function getProxyPort(): int
    {
        return $this->proxyPort;
    }

    /**
     * @param int $proxyPort
     */
    public function setProxyPort(int $proxyPort): void
    {
        $this->proxyPort = $proxyPort;
    }

    /**
     * {@inherticdoc}
     */
    public function getRequestUri(): string
    {
        $uri = self::URI;

        return $uri;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return Request::METHOD_POST;
    }
}