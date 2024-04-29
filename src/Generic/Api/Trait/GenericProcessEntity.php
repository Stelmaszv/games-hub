<?php

namespace App\Generic\Api\Trait;

use ReflectionClass;
use Symfony\Component\Uid\Uuid;
use App\Generic\Api\Interfaces\DTO;
use Doctrine\Persistence\ManagerRegistry;
use App\Generic\Api\Interfaces\ProcessEntity;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\SerializerInterface;
use App\Generic\Api\Identifier\Interfaces\IdentifierUid;
use PhpParser\Node\Stmt\Continue_;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait GenericProcessEntity
{
    protected ?string $entity = null;
    protected ?string $dto = null;
    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;
    protected ManagerRegistry $managerRegistry;
    protected Request $request;

    protected function afterProcessEntity(): void {}

    private function checkData()
    {
        if(!$this->entity) {
            throw new \Exception("Entity is not define in controller ".get_class($this)."!");
        }

        if(!$this->dto) {
            throw new \Exception("Dto is not define in controller ".get_class($this)."!");
        }
    }

    private function processEntity(DTO $dto): void
    {
        $entity = $this->getEntity();
        $reflectionClass = new ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();
    
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyType = $property->getType();

            if (!$property->isStatic()) {
                if ($propertyType !== null) {
                    $propertyTypeName = $propertyType->__toString();
                    $method = 'set' . ucfirst($propertyName);
    
                    if ($method === 'setId') {
                        continue;
                    }
    
                    if ($propertyType->__toString() === 'Doctrine\Common\Collections\Collection') {
                        foreach($dto->$propertyName as $collectionEl){
                            $namespace = "App\Entity\Publisher";
                            $class = new $namespace;
                            $objectRepository = $this->managerRegistry->getRepository($class::class);
                            $el = $objectRepository->find(2);

                            $method = 'addPublisher';
                            $entity->$method($el);
                        }
                    } else {
                        $object = $this->getObject($propertyTypeName);
                        if ($object !== null && property_exists($dto, $propertyName) && $dto->$propertyName !== null) {
                            $objectRepository = $this->managerRegistry->getRepository($object::class);
                            $entity->$method($objectRepository->find($dto->$propertyName));
                        } else {
                            $entity->$method($dto->$propertyName);
                        }
                    }
                } else {
                    $propertyName = $property->getName();
                    $method = 'set' . ucfirst($propertyName);
                    $entity->$method($dto->$propertyName);
                }
            }
        }

        if($entity instanceof IdentifierUid && $this instanceof ProcessEntity){
            $entity->setId(Uuid::v4());
        }

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();

        $this->insertId = $entity->getId();

    }
    
    private function processCollection($data, $collectionType)
    {
        $collection = new ArrayCollection();
    
        foreach ($data as $item) {
            $collection->add($item);
        }
    
        return $collection;
    }
    

    private function getObject(string $type): ?object
    {
        $type = ltrim($type, '?');

        if (strpos($type, '\\') === false) {
            return null;
        }

        $nameSpace = '\\' . $type;

        return new $nameSpace;
    }
}
