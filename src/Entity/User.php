<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Generic\Auth\UserRepository;
use App\Generic\Auth\AuthenticationEntity;
use App\Generic\Api\Trait\EntityApiGeneric;
use Doctrine\Common\Collections\Collection;
use App\Generic\Api\Interfaces\ApiInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Generic\Api\Identifier\Trait\IdentifierById;
use App\Generic\Api\Identifier\Interfaces\IdentifierId;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, IdentifierId,ApiInterface
{
	use AuthenticationEntity;
    use IdentifierById;
    use EntityApiGeneric;
}