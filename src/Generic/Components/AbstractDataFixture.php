<?php

declare(strict_types=1);

namespace App\Generic\Components;

use Doctrine\Persistence\ObjectManager;
use App\Generic\Api\Identifier\Interfaces\IdentifierUid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

abstract class AbstractDataFixture
{
    protected UserPasswordHasherInterface $passwordEncoder;
    protected ObjectManager $objectManager;

    protected ?string $enetity = null;
    protected array $data = [];

    public function __construct(UserPasswordHasherInterface $userPasswordHasher,ObjectManager $objectManager)
    {
        $this->passwordEncoder = $userPasswordHasher;
        $this->objectManager = $objectManager;

        if($this->enetity === null){
            throw new \Exception("Entity is not define in Fixture ".get_class($this)."!");
        }
    }

    public function setData(){
        foreach($this->data as $elelemnts){
            $enetityObj = new $this->enetity();
            $idetikatorUid = $enetityObj instanceof IdentifierUid;

            if($idetikatorUid){
                $enetityObj?->setId(Uuid::v4());
            }
            
            foreach($elelemnts as $field => $value){

                    $setMethod = "set" . ucfirst($field);

                    if (method_exists($this, "on" . ucfirst($field) . "Set")) {
                        $onMethodSet = "on" . ucfirst($field) . "Set";
                        $value = $this->$onMethodSet($value, $enetityObj);
                    }

                    $enetityObj?->$setMethod($value);
            }
            
            $this->objectManager->persist($enetityObj);
            $this->objectManager->flush();
            
        }
    }
}
