<?php

namespace ProxyProviderBridge\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Checks if the external proxy services are reachable
 */
class IsProxyReachableRequest extends BaseRequest
{
    private const URI = "is-proxy-reachable";

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
        return Request::METHOD_GET;
    }
}