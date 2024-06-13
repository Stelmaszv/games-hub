<?php

namespace App\Security\Voter;

use App\Generic\Auth\JWT;
use App\Roles\RoleAdmin;
use App\Roles\RoleSuperAdmin;
use App\Roles\RoleUser;
use App\Security\Atribute;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BaseUserVoter extends Voter
{
    public const ATTRIBUTES = [];

    protected function supports(string $attribute, mixed $subject): bool
    {
        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return false;
    }
}
