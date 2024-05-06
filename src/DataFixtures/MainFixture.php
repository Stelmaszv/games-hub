<?php


namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Data\PublisherData;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MainFixture extends Fixture
{
    private array  $data = [
        UserData::class,
        PublisherData::class
    ];

    protected ManagerRegistry $managerRegistry;
    protected UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordHasher, ManagerRegistry $managerRegistry)
    {
        $this->passwordEncoder = $passwordHasher;
        $this->managerRegistry = $managerRegistry;
    }
    
    public function load(ObjectManager $manager)
    {
      foreach($this->data as $fixture){
        $fixture = new $fixture($this->passwordEncoder,$manager,$this->managerRegistry);
        $fixture->setData();
      }
    }
}