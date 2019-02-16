<?php
namespace NaiveUserState;

class CookieResponseService
{

    private $cookies = [];

    public function addCookie(Cookie $cookie): void
    {
        if (isset($this->cookies[$cookie->getName()])) {
            throw new RuntimeException("Cookie already added: {$cookie->getName()}");
        }

        $this->cookies[$cookie->getName()] = $cookie;
    }

    public function removeCookie(string $name): void
    {
        if (!isset($this->cookies[$name])) {
            throw new RuntimeException("Cookie not added: {$name}");
        }

        unset($this->cookies[$name]);
    }

    public function hasCookie(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * @return Cookie[]
     */
    public function listCookies(): array
    {
        return $this->cookies;
    }
}
