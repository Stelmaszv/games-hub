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
    protected ?string $enetity = null;
    protected array $data = [];

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordEncoder = $userPasswordHasher;

        if (null === $this->enetity) {
            throw new \Exception('Entity is not define in Fixture '.get_class($this).'!');
        }
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->data as $elelemnts) {
            $enetityObj = new $this->enetity();
            $idetikatorUid = $enetityObj instanceof IdentifierUid;

            if ($idetikatorUid) {
                $enetityObj?->setId(Uuid::v4());
            }

            foreach ($elelemnts as $field => $value) {
                $setMethod = 'set'.ucfirst($field);

                if (method_exists($this, 'on'.ucfirst($field).'Set')) {
                    $onMethodSet = 'on'.ucfirst($field).'Set';
                    $value = $this->$onMethodSet($value, $enetityObj);
                }

                $enetityObj?->$setMethod($value);
            }

            $manager->persist($enetityObj);
            $manager->flush();
        }
    }
}
