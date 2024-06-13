<?php

namespace App\Generic\Components;

abstract class AbstractJsonMapper
{
    protected bool $multi = false;

    public function isValid(mixed $value): bool
    {
        $jsonMap = false;

        if ($this->multi) {
            $this->validMultiMapper($value);
            $jsonMap = true;
        }

        if (!$this->multi) {
            $this->validMapper($value);
            $jsonMap = true;
        }

        if (false === $jsonMap) {
            throw new \Exception('Invalid Json in '.get_class($this).'!');
        }

        return true;
    }

    /**
     * @param array<mixed> $value
     */
    private function validMultiMapper(array $value): void
    {
        foreach ($value as $el) {
            $this->validMapper(get_object_vars($el));
        }
    }

    /**
     * @param array<mixed> $value
     */
    private function validMapper(array $value): void
    {
        foreach ($value as $jEl => $key) {
            if (!isset($this->jsonSchema()[$jEl])) {
                throw new \Exception('Invalid Json field  '.$jEl.' not Exist in '.get_class($this).' !');
            }

            if (!$this->validType($this->jsonSchema()[$jEl], $key)) {
                throw new \Exception('Invalid Json type for '.$jEl.' valid is '.$this->jsonSchema()[$jEl].' given '.$key.' in '.get_class($this).'!');
            }
        }
    }

    private function validType(string $type, mixed $value): bool
    {
        if ('' === $type) {
            throw new \Exception('Invalid type in ' . get_class($this) . '!');
        }
    
        if ($type !== null) {
            return match ($type) {
                'string' => is_string($value),
                'bool' => is_bool($value),
                'int' => is_int($value),
                'non-empty-string' => is_string($value) && $value !== '',
                default => false, // Obsługa pozostałych przypadków
            };
        }
    }

    /**
     * @return array<mixed> $value
     */
    abstract public function jsonSchema(): array;

    /**
     * @return array<mixed> $value
     */
    abstract public function defaultValue(): array;
}
