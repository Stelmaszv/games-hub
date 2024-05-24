<?php

namespace App\Security\Voter;

use App\Generic\Auth\JWT;
use App\Roles\RoleAdmin;
use App\Roles\RoleSuperAdmin;
use App\Roles\RoleUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BaseUserVoter extends Voter
{
    public const USER = 'ROLE_USER';

    private JWT $jwtService;

    public function __construct(JWT $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, RoleUser::ROLES, true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->jwtService->decode($this->jwtService->getJWTFromHeader());
        $isSuperAdmin = \in_array(RoleSuperAdmin::NAME, $user['roles'], true);
        $isAdmin = \in_array(RoleAdmin::NAME, $user['roles'], true);
        $isEditor = $subject?->isEditor($user['id']);
        $isVerified = $subject?->getVerified();

        if ($isVerified || $isEditor || $isSuperAdmin || $isAdmin) {
            return true;
        }

        return true;
    }
}
