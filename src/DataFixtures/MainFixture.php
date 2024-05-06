<?php


namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MainFixture extends Fixture
{
    private array  $data = [
        UserData::class
    ];

    protected UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordEncoder = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager)
    {
      foreach($this->data as $fixture){
        $fixture = new $fixture($this->passwordEncoder,$manager);
        $fixture->setData();
      }
    }
}