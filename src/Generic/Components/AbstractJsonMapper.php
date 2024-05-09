<?php

namespace App\Generic\Components;

abstract class AbstractJsonMapper
{
    protected bool $multi = false;

    public function isValid(mixed $value): bool
    {
        $maper = false;

        if ($this->multi) {
            $this->validMultiMapper($value);
            $maper = true;
        }

        if (!$this->multi) {
            $this->validMapper($value);
            $maper = true;
        }

        if (false === $maper) {
            throw new \Exception('Invalid Json in '.get_class($this).'!');
        }

        return true;
    }

    private function is2dArray(array $array): bool
    {
        if (is_array($array) && count($array) > 0) {
            return is_array(array_shift($array));
        }

        return false;
    }

    private function validMultiMapper(array $value): void
    {
        foreach ($value as $el) {
            $this->validMapper(get_object_vars($el));
        }
    }

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
            throw new \Exception('Invalid type in '.get_class($this).'!');
        }

        return match ($type) {
            'string' => is_string($value),
            'bool' => is_bool($value),
            'int' => is_int($value),
        };
    }

    abstract public function jsonSchema(): array;

    abstract public function defaultValue(): array;
}
