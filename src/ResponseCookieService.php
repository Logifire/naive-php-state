<?php
namespace NaivePhpState;

class ResponseCookieService
{

    private $cookies = [];

    public function add(ResponseCookie $cookie): void
    {
        if (isset($this->cookies[$cookie->getName()])) {
            throw new RuntimeException("Cookie already added: {$cookie->getName()}");
        }

        $this->cookies[$cookie->getName()] = $cookie;
    }

    public function remove(string $name): void
    {
        if (!isset($this->cookies[$name])) {
            throw new RuntimeException("Cookie not added: {$name}");
        }

        unset($this->cookies[$name]);
    }

    public function has(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * @return ResponseCookie[]
     */
    public function list(): array
    {
        return $this->cookies;
    }
}
