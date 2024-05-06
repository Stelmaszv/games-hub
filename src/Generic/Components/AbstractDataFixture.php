<?php

declare(strict_types=1);

namespace App\Generic\Components;

use Doctrine\Persistence\ObjectManager;
use App\Generic\Api\Identifier\Interfaces\IdentifierUid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractDataFixture
{
    protected UserPasswordHasherInterface $passwordEncoder;
    protected ObjectManager $objectManager;
    protected ManagerRegistry $managerRegistry;
    protected ?string $entity = null;
    protected array $data = [];

    public function __construct(UserPasswordHasherInterface $passwordEncoder, ObjectManager $objectManager, ManagerRegistry $managerRegistry)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->objectManager = $objectManager;
        $this->managerRegistry = $managerRegistry;

        if ($this->entity === null) {
            throw new \Exception("Entity is not defined in Fixture " . get_class($this) . "!");
        }
    }

    public function setData()
    {
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

        $this->objectManager->flush();
    }
}
