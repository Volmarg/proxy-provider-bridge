<?php

namespace ProxyProviderBridge\Request;

/**
 * Base class for building any bridge between services
 *
 * @package ProxyProviderBridge\Request
 */
abstract class BaseRequest
{
    private string $companyName;

    /**
     * Request Uri to be called
     *
     * @return string
     */
    public abstract function getRequestUri(): string;

    /**
     * GET, POST etc.
     *
     * @return string
     */
    public abstract function getRequestMethod(): string;

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

}