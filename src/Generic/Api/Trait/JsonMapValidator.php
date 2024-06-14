<?php

namespace App\Generic\Api\Trait;

use App\Generic\Components\AbstractJsonMapper;

trait JsonMapValidator
{
    /**
     * @param array<mixed> $value
     *
     * @return array<mixed>
     */
    private function jsonValidate(?array $value, string $mapper): array
    {
        $mapperObj = new $mapper();

        if (!$mapperObj instanceof AbstractJsonMapper) {
            throw new \InvalidArgumentException('Invalid Instance !');
        }

        if (null === $value) {
            return $mapperObj->defaultValue();
        }

        if (0 === count($value)) {
            return $mapperObj->defaultValue();
        }

        if ($mapperObj->isValid($value)) {
            return $value;
        }

        return $mapperObj->defaultValue();
    }
}
