<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Publisher;
use App\Generic\Auth\JWT;
use App\Roles\RoleAdmin;
use App\Roles\RolePublisherCreator;
use App\Roles\RolePublisherEditor;
use App\Roles\RoleSuperAdmin;
use App\Security\Atribute;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PublisherBaseVoter extends Voter
{
    public const USER = RoleSuperAdmin::NAME;
    public const ATRIBUTES = [
        Atribute::CAN_ADD_PUBLISHER,
        Atribute::CAN_DELETE_PUBLISHER,
        Atribute::CAN_EDIT_PUBLISHER,
    ];

    private JWT $jwtService;
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack, JWT $jwtService)
    {
        $this->requestStack = $requestStack;
        $this->jwtService = $jwtService;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, self::ATRIBUTES) && $subject instanceof Publisher
              || \in_array($attribute, RoleSuperAdmin::ROLES, true)
              || \in_array($attribute, RoleAdmin::ROLES, true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->jwtService->decode($this->jwtService->getJWTFromHeader());
        $userHasSuperRule = in_array(RoleSuperAdmin::NAME, $user['roles']);
        $isAdmin = \in_array(RoleAdmin::NAME, $user['roles'], true);

        if ($userHasSuperRule || $isAdmin) {
            return true;
        }

        switch ($attribute) {
            case Atribute::CAN_DELETE_PUBLISHER:
                $userHasRule = $this->userHasRule($user, RolePublisherEditor::NAME);

                return $subject->isEditor($user['id']) && $userHasRule;
                break;

            case Atribute::CAN_ADD_PUBLISHER:
                $userHasRule = $this->userHasRule($user, RolePublisherCreator::NAME);

                return $userHasRule || $userHasSuperRule;
                break;

            case Atribute::CAN_EDIT_PUBLISHER:
                $userHasRule = $this->userHasRule($user, RolePublisherEditor::NAME);

                return ($subject->isEditor($user['id']) && $userHasRule) || $userHasSuperRule;
                break;
        }

        return false;
    }

    private function userHasRule(array $user, string $role)
    {
        return in_array($role, $user['roles']);
    }
}
