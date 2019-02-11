<?php
namespace NaiveUserState;

use RuntimeException;

class SessionCollection
{

    /**
     * @var array
     */
    protected $data;

    public function __construct(array &$data)
    {
        $this->data = &$data;
    }

    public function hasKey(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function isString(string $name): bool
    {
        return is_string($this->data[$name]);
    }

    public function getString(string $name): string
    {
        if (!$this->isString($name)) {
            throw new RuntimeException("No string mathches for {$name}");
        }
        return $this->data[$name];
    }

    public function setString(string $name, string $value): void
    {
        if (isset($this->data[$name])) {
            throw new RuntimeException("Existing key: {$name}");
        }

        $this->data[$name] = $value;
    }

    public function isInteger(string $name): bool
    {
        return is_numeric($this->data[$name]);
    }

    public function getInetger(string $name): int
    {
        if (!$this->isInteger($name)) {
            throw new RuntimeException("No integer matches for {$name}");
        }

        return (int) $this->data[$name];
    }

    public function setInteger(string $name, int $value): void
    {
        if (isset($this->data[$name])) {
            throw new RuntimeException("Existing key: {$name}");
        }

        $this->data[$name] = $value;
    }

    public function isCollection(string $name): bool
    {
        return isset($this->data[$name]) && is_array($this->data[$name]);
    }

    public function getCollection(string $name): array
    {
        if (!$this->isCollection($name)) {
            throw new RuntimeException("No collection matches for {$name}");
        }

        return $this->data[$name];
    }

    public function setCollection(string $name, array $value): void
    {
        if (isset($this->data[$name])) {
            throw new RuntimeException("Existing key: {$name}");
        }

        $this->data[$name] = $value;
    }
}
