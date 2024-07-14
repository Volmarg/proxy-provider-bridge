<?php

namespace ProxyProviderBridge\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Provides the proxy connection data
 */
class GetConnectionDataRequest extends BaseRequest
{
    private const URI = "get-proxy-connection-data";

    /**
     * @var string|null
     */
    private ?string $proxyInternalId = null;
    private ?string $usage = null;
    private ?string $countryIsoCode = null;
    private ?string $provider = null;

    /**
     * @return string|null
     */
    public function getUsage(): ?string
    {
        return $this->usage;
    }

    /**
     * @param string|null $usage
     */
    public function setUsage(?string $usage): void
    {
        $this->usage = $usage;
    }

    /**
     * @return string|null
     */
    public function getCountryIsoCode(): ?string
    {
        return $this->countryIsoCode;
    }

    /**
     * @param string|null $countryIsoCode
     */
    public function setCountryIsoCode(?string $countryIsoCode): void
    {
        $this->countryIsoCode = $countryIsoCode;
    }

    /**
     * @return string|null
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /**
     * @param string|null $provider
     */
    public function setProvider(?string $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * @return string|null
     */
    public function getProxyInternalId(): ?string
    {
        return $this->proxyInternalId;
    }

    /**
     * @param string|null $proxyInternalId
     */
    public function setProxyInternalId(?string $proxyInternalId): void
    {
        $this->proxyInternalId = $proxyInternalId;
    }

    /**
     * {@inherticdoc}
     */
    public function getRequestUri(): string
    {
        return self::URI . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return Request::METHOD_POST;
    }
}