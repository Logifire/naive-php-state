<?php
namespace NaivePhpState;

use RuntimeException;

/**
 * This wraps the $_SESSION superglobal
 */
class SessionCollection
{

    /**
     * @var array PHPs superglobal $_SESSION
     */
    protected $session;

    public function __construct()
    {
        $this->session = &$_SESSION;
    }

    public function hasKey(string $name): bool
    {
        return isset($this->session[$name]);
    }

    public function removeKey(string $name): void
    {
        unset($this->session[$name]);
    }

    public function isString(string $name): bool
    {
        return is_string($this->session[$name]);
    }

    public function getString(string $name): string
    {
        if (!$this->isString($name)) {
            throw new RuntimeException("No string mathches for {$name}");
        }
        return $this->session[$name];
    }

    public function setString(string $name, string $value): void
    {
        if (isset($this->session[$name])) {
            throw new RuntimeException("Existing key: {$name}");
        }

        $this->session[$name] = $value;
    }

    public function isInteger(string $name): bool
    {
        return is_numeric($this->session[$name]);
    }

    public function getInetger(string $name): int
    {
        if (!$this->isInteger($name)) {
            throw new RuntimeException("No integer matches for {$name}");
        }

        return (int) $this->session[$name];
    }

    public function setInteger(string $name, int $value): void
    {
        if (isset($this->session[$name])) {
            throw new RuntimeException("Existing key: {$name}");
        }

        $this->session[$name] = $value;
    }

    public function isCollection(string $name): bool
    {
        return isset($this->session[$name]) && is_array($this->session[$name]);
    }

    public function getCollection(string $name): array
    {
        if (!$this->isCollection($name)) {
            throw new RuntimeException("No collection matches for {$name}");
        }

        return $this->session[$name];
    }

    public function setCollection(string $name, array $value): void
    {
        if (isset($this->session[$name])) {
            throw new RuntimeException("Existing key: {$name}");
        }

        $this->session[$name] = $value;
    }
}
