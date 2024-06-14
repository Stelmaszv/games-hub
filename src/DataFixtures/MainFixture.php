<?php

namespace App\DataFixtures;

use App\DataFixtures\Data\DeveloperData;
use App\DataFixtures\Data\PublisherData;
use App\DataFixtures\Data\UserData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MainFixture extends Fixture
{
    /**
     * @var array<string>
     */
    private array $data = [
        UserData::class,
        PublisherData::class,
        DeveloperData::class,
    ];

    protected ManagerRegistry $managerRegistry;
    protected UserPasswordHasherInterface $passwordEncoder;
    protected EntityManager $entityManager;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $managerRegistry,
        EntityManager $entityManager
    ) {
        $this->passwordEncoder = $passwordHasher;
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->data as $fixture) {
            $fixture = new $fixture($this->passwordEncoder, $manager, $this->managerRegistry, $this->entityManager);
            $fixture->setData();
        }
    }
}
