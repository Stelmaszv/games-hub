<?php

namespace App\Entity;

use App\Generic\Api\Identifier\Interfaces\IdentifierId;
use App\Generic\Api\Identifier\Trait\IdentifierById;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Trait\EntityApiGeneric;
use App\Generic\Auth\AuthenticationEntity;
use App\Generic\Auth\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, IdentifierId, ApiInterface
{
    use AuthenticationEntity;
    use IdentifierById;
    use EntityApiGeneric;
}
