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
    public const ATRIBUTES = [
        Atribute::CAN_LIST_PUBLISHERS,
        Atribute::CAN_SHOW_PUBLISHER,
    ];

    private JWT $jwtService;

    public function __construct(JWT $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, RoleUser::ROLES, true)
               || \in_array($attribute, RoleSuperAdmin::ROLES, true)
               || \in_array($attribute, RoleAdmin::ROLES, true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->jwtService->decode($this->jwtService->getJWTFromHeader());
        $isSuperAdmin = \in_array(RoleSuperAdmin::NAME, $user['roles'], true);
        $isAdmin = \in_array(RoleAdmin::NAME, $user['roles'], true);
        $isEditor = $subject?->isEditor($user['id']);
        $isVerified = $subject?->getVerified();

        if (null !== $subject?->getCreatedBy() && null !== $user['id']) {
            $isCreator = $subject?->getCreatedBy()['id'] === $user['id'];
        }

        switch ($attribute) {
            case Atribute::CAN_LIST_PUBLISHERS:
                return true;
                break;

            case Atribute::CAN_SHOW_PUBLISHER:
                if ($isVerified || $isEditor || $isSuperAdmin || $isAdmin || $isCreator) {
                    return true;
                }
                break;
        }

        return false;
    }
}
