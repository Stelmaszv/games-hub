<?php

namespace App\Generic\Api\Trait;

use App\Generic\Api\Identifier\Interfaces\IdentifierUid;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Interfaces\DTO;
use App\Generic\Api\Interfaces\ProcessEntity;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait GenericProcessEntity
{
    protected ?string $entity = null;
    protected ?string $dto = null;
    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;
    protected ManagerRegistry $managerRegistry;
    protected Request $request;

    protected function afterProcessEntity(): void
    {
    }

    private function checkData() : void
    {
        if (!$this->entity) {
            throw new \Exception('Entity is not define in controller '.get_class($this).'!');
        }

        if (!$this->dto) {
            throw new \Exception('Dto is not define in controller '.get_class($this).'!');
        }
    }

    private function processEntity(DTO $dto): void
    {
        $entity = $this->getEntity();
        $reflectionClass = new \ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyType = $property->getType();

            if (null !== $propertyType) {
                $propertyTypeName = $propertyType->__toString();
                $method = 'set'.ucfirst($propertyName);

                if ('setId' === $method) {
                    continue;
                }

                if ('Doctrine\Common\Collections\Collection' === $propertyType->__toString()) {
                    $this->handleCollection($dto, $propertyName, $entity);
                } else {
                    $this->handleSingleEntity($dto, $propertyName, $entity, $propertyTypeName, $method);
                }
            } else {
                $this->handleNonTypedProperty($dto, $propertyName, $entity);
            }
        }

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();

        $this->insertId = $entity->getId();
    }

    private function handleCollection(DTO $dto, string $propertyName, ApiInterface $entity): void
    {
        foreach ($dto->$propertyName as $collectionEl) {
            $name = basename(str_replace('\\', '/', get_class($collectionEl)), 'DTO');
            $namespace = "App\Entity\\".$name;
            $class = new $namespace();
            $objectRepository = $this->managerRegistry->getRepository($class::class);
            $el = $objectRepository->find($collectionEl->id);
            $method = 'add'.ucfirst($name);
            $entity->$method($el);
        }
    }

    private function handleSingleEntity(DTO $dto, string $propertyName,ApiInterface  $entity, string $propertyTypeName, string $method): void
    {
        $object = $this->getObject($propertyTypeName);
        if (null !== $object && property_exists($dto, $propertyName) && $dto->$propertyName !== null) {
            $objectRepository = $this->managerRegistry->getRepository($object::class);
            $entity->$method($objectRepository->find($dto->$propertyName));
        } else {
            $entity->$method($dto->$propertyName);
        }
    }

    private function handleNonTypedProperty(DTO $dto, string $propertyName,ApiInterface $entity): void
    {
        $method = 'set'.ucfirst($propertyName);
        $entity->$method($dto->$propertyName);
    }

    private function getObject(string $type): ?object
    {
        $type = ltrim($type, '?');

        if (false === strpos($type, '\\')) {
            return null;
        }

        $nameSpace = '\\'.$type;

        return new $nameSpace();
    }
}
