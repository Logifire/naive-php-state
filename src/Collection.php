<?php
namespace NaiveUserState;

interface Collection
{

    public function hasString(string $name): bool;

    public function getString(string $name): string;

    public function setString(string $name, string $value): void;

    public function hasInteger(string $name): bool;

    public function getInetger(string $name): int;

    public function setInteger(string $name, int $value): void;

    public function hasCollection(string $name): bool;

    public function getCollection(string $name): array;

    public function setCollection(string $name, array $value): void;
}
