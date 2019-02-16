<?php
namespace NaiveUserState;

class ResponseCookie
{

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $value = '';

    /**
     * @var int 
     */
    private $expires = 0;

    /**
     * @var string
     */
    private $path = '/';

    /**
     * @var string
     */
    private $domain = '';

    /**
     * @var bool
     */
    private $secure = false;

    /**
     * @var bool
     */
    private $http_only = false;

    /**
     * @var string
     */
    private $same_site = '';

    // TODO: Max-Age

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * The value 0 means "until the browser is closed", session cookie.
     */
    public function setExpires(int $expires): void
    {
        $this->expires = $expires;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function setSecure(bool $secure): void
    {
        $this->secure = $secure;
    }

    public function setHttpOnly(bool $http_only): void
    {
        $this->http_only = $http_only;
    }

    public function setSameSite(string $same_site): void
    {
        $this->same_site = $same_site;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function isHttpOnly(): bool
    {
        return $this->http_only;
    }

    public function getSameSite(): string
    {
        return $this->same_site;
    }
}
