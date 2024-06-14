<?php

namespace App\Generic\Api\Trait;

use App\Generic\Api\Interfaces\ApiInterface;
use Doctrine\Common\Collections\Collection;

trait EntityApiGeneric
{
    /**
     * @return array<mixed>
     */
    public function setApiGroup(ApiInterface $entityObject, string $objectProperty): ?array
    {
        if ($this->$objectProperty === null) {
            return null;
        }

        $values = null;

        $reflectionClass = new \ReflectionClass($entityObject);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $getterMethod = 'get'.ucfirst($propertyName);

            if (!is_object($this->$objectProperty->$getterMethod())) {
                $values[$propertyName] = $this->$objectProperty->$getterMethod();
            }
        }

        return $values;
    }

    /**
     * @return array<mixed>
     */
    public function setApiGroupMany(ApiInterface $entityObject, Collection $collection): array
    {
        $values = [];

        foreach ($collection as $el) {
            $reflectionClass = new \ReflectionClass($entityObject);
            $properties = $reflectionClass->getProperties();
            $entity = null;
            foreach ($properties as $property) {
                $propertyName = $property->getName();
                $getterMethod = 'get'.ucfirst($propertyName);

                if (!is_object($el->$getterMethod())) {
                    $entity[$propertyName] = $el->$getterMethod();
                }
            }
            if($entity !== null){
                $values[] = $entity;
            }
        }

        return $values;
    }
}
