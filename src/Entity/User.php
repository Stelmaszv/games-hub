<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Generic\Auth\UserRepository;
use App\Generic\Auth\AuthenticationEntity;
use App\Generic\Api\Identifier\Trait\IdentifierByUid;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Generic\Api\Identifier\Interfaces\IdentifierUid;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, IdentifierUid
{
	use AuthenticationEntity;
    use IdentifierByUid;
}