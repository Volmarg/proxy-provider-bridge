<?php

namespace ProxyProviderBridge\Dto;

class ConnectionDataDto
{
    private string  $ip;
    private int     $port;
    private ?string $username = null;
    private ?string $password = null;

    /**
     * That's special flag that is for example used in web-scrapper-bundle,
     * when system is on dev, and no connection was found, this way dev
     * works without proxy (which is not allowed on prod),
     *
     * @var bool
     */
    private bool $exists = true;

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function hasCredentials(): bool
    {
        return (!empty($this->getUsername()) && !empty($this->getPassword()));
    }

    /**
     * @param bool $isAuthAllowed
     *
     * @return string
     */
    public function getProxyString(bool $isAuthAllowed = true): string
    {
        $credentialsPart = "";
        if ($this->hasCredentials() && $isAuthAllowed) {
            $credentialsPart = "{$this->getUsername()}:{$this->getPassword()}@";
        }

        return "{$credentialsPart}{$this->getIp()}:{$this->getPort()}";
    }

    /**
     * @return bool
     */
    public function doesExists(): bool
    {
        return $this->exists;
    }

    /**
     * @param bool $exists
     */
    public function setExists(bool $exists): void
    {
        $this->exists = $exists;
    }

    /**
     * @return ConnectionDataDto
     */
    public static function createNotExisting(): ConnectionDataDto
    {
        $dto = new ConnectionDataDto();
        $dto->setIp("");
        $dto->setPort(0);
        $dto->setExists(false);

        return $dto;
    }

}