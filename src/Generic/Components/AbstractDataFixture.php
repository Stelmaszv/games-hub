<?php

declare(strict_types=1);

namespace App\Generic\Components;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Identifier\Interfaces\IdentifierUid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractDataFixture
{
    protected UserPasswordHasherInterface $passwordEncoder;
    protected ObjectManager $objectManager;
    protected ManagerRegistry $managerRegistry;
    protected EntityManager $entityManager;
    
    protected ?string $entity = null;
    protected array $data = [];

    public function __construct(UserPasswordHasherInterface $passwordEncoder, ObjectManager $objectManager, ManagerRegistry $managerRegistry, EntityManager $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->objectManager = $objectManager;
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;

        if ($this->entity === null) {
            throw new \Exception("Entity is not defined in Fixture " . get_class($this) . "!");
        }
    }

    public function setData()
    {
        if (empty($this->data)) {
            throw new \Exception("No data provided to setData() method.");
        }

        foreach ($this->data as $elements) {
            $entityObj = new $this->entity();
            $identifierUid = $entityObj instanceof IdentifierUid;

            if ($identifierUid) {
                $entityObj->setId(Uuid::uuid4());
            }

            foreach ($elements as $field => $value) {
                $setMethod = "set" . ucfirst($field);

                if (method_exists($this, "on" . ucfirst($field) . "Set")) {
                    $onMethodSet = "on" . ucfirst($field) . "Set";
                    $value = $this->$onMethodSet($value, $entityObj);
                }

                $entityObj->$setMethod($value);
            }

            $this->objectManager->persist($entityObj);
        }
        $this->initRelations($entityObj);
        $this->objectManager->flush();
    }

    protected function initRelations(ApiInterface $entityObj): void {}

    protected function addRelation(string $filed, ApiInterface $entityObj, ?ApiInterface $object) {

        if($object === null){
            throw new \Exception("Releted object not found !");
        } 

        $fieldMethod = 'add'.$filed;
        $entityObj->$fieldMethod($object);
    }
}
