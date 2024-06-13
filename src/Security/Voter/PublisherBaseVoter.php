<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Publisher;
use App\Generic\Auth\JWT;
use App\Roles\RoleAdmin;
use App\Roles\RolePublisherCreator;
use App\Roles\RolePublisherEditor;
use App\Roles\RoleSuperAdmin;
use App\Roles\RoleUser;
use App\Security\Atribute;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PublisherBaseVoter extends Voter
{
    public const USER = RoleSuperAdmin::NAME;
    public const ATTRIBUTES = [
        Atribute::CAN_ADD_PUBLISHER,
        Atribute::CAN_DELETE_PUBLISHER,
        Atribute::CAN_EDIT_PUBLISHER,
        Atribute::CAN_ADD_PUBLISHER,
        Atribute::CAN_LIST_PUBLISHERS,
        Atribute::CAN_SHOW_PUBLISHER,
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
        $user = $this->jwtService->decode($this->jwtService->getJWTFromHeader());
        $userHasSuperRule = in_array(RoleSuperAdmin::NAME, $user['roles']);
        $isAdmin = \in_array(RoleAdmin::NAME, $user['roles'], true);
        $isUser = \in_array($attribute, RoleUser::ROLES, true);

        return in_array($attribute, self::ATTRIBUTES) && $subject instanceof Publisher 
            || ($userHasSuperRule || $isAdmin) || $isUser;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->jwtService->decode($this->jwtService->getJWTFromHeader());
        $userHasSuperRule = in_array(RoleSuperAdmin::NAME, $user['roles']);
        $isAdmin = \in_array(RoleAdmin::NAME, $user['roles'], true);
        $isVerified = $subject?->getVerified();
        $isEditor = $subject?->isEditor($user['id']);

        $isCreator = null;
        if (null !== $subject?->getCreatedBy() && null !== $user['id']) {
            $isCreator = $subject?->getCreatedBy()['id'] === $user['id'];
        }


        if ($userHasSuperRule || $isAdmin) {
            return true;
        }

        switch ($attribute) {
            case Atribute::CAN_DELETE_PUBLISHER:
                $userHasRule = $this->userHasRule($user, RolePublisherEditor::NAME);
                return $subject->isEditor($user['id']) && $userHasRule;
          
            case Atribute::CAN_ADD_PUBLISHER:
                return $this->userHasRule($user, RolePublisherCreator::NAME);
    
            case Atribute::CAN_EDIT_PUBLISHER:
                $userHasRule = $this->userHasRule($user, RolePublisherEditor::NAME);

                return $subject->isEditor($user['id']) && $userHasRule;

            case Atribute::CAN_LIST_PUBLISHERS:
                return true;
    
            case Atribute::CAN_SHOW_PUBLISHER:
                return ($isVerified || $isEditor || $isCreator);
    
        }

        return false;
    }

    /**
     * @param array<array<string>> $user
     */
    private function userHasRule(array $user, string $role) :bool
    {
        return in_array($role, $user['roles']);
    }
}
