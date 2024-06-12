<?php

namespace App\Generic\Components;

use App\Generic\Api\Identifier\Interfaces\IdentifierUid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

abstract class AbstractFixtureGeneric extends Fixture
{
    protected UserPasswordHasherInterface $passwordEncoder;
    protected ?string $entity = null;
    protected array $data = [];

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordEncoder = $userPasswordHasher;

        if (null === $this->entity) {
            throw new \Exception('Entity is not define in Fixture '.get_class($this).'!');
        }
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->data as $elements) {
            $entityObj = new $this->entity();
            $initiatorUid = $entityObj instanceof IdentifierUid;

            if ($initiatorUid) {
                $entityObj?->setId(Uuid::v4());
            }

            foreach ($elements as $field => $value) {
                $setMethod = 'set'.ucfirst($field);

                if (method_exists($this, 'on'.ucfirst($field).'Set')) {
                    $onMethodSet = 'on'.ucfirst($field).'Set';
                    $value = $this->$onMethodSet($value, $entityObj);
                }

                $entityObj?->$setMethod($value);
            }

            $manager->persist($entityObj);
            $manager->flush();
        }
    }
}
