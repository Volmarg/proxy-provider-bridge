<?php

namespace ProxyProviderBridge\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Updates the call status
 */
class UpdateCallDataRequest extends BaseRequest
{
    private const URI = "update-call-data";

    private int $id;
    private bool $success;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * {@inherticdoc}
     */
    public function getRequestUri(): string
    {
        $uri = self::URI . DIRECTORY_SEPARATOR . $this->getId() . DIRECTORY_SEPARATOR . (int)$this->isSuccess();

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