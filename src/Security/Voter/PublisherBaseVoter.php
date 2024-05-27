<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Roles\RoleAdmin;
use App\Entity\Publisher;
use App\Generic\Auth\JWT;
use App\Security\Atribute;
use App\Roles\RoleSuperAdmin;
use App\Roles\RolePublisherEditor;
use App\Roles\RolePublisherCreator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PublisherBaseVoter extends Voter
{
    public const ATRIBUTES = [
        Atribute::CAN_ADD_PUBLISHER,
        Atribute::CAN_DELETE_PUBLISHER,
        Atribute::CAN_EDIT_PUBLISHER
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
        return in_array($attribute, SElf::ATRIBUTES) && $subject instanceof Publisher;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->jwtService->decode($this->jwtService->getJWTFromHeader());
        $userHasSuperRule = in_array(RoleSuperAdmin::NAME, $user['roles']);
        $isAdmin = \in_array(RoleAdmin::NAME, $user['roles'], true);

        switch($attribute){
            
            case Atribute::CAN_DELETE_PUBLISHER;
                $userHasRule = $this->userHasRule($user,RolePublisherEditor::NAME);
            
                return ($subject->isEditor($user['id']) && $userHasRule) || $userHasSuperRule || $isAdmin;
            break;

            case Atribute::CAN_ADD_PUBLISHER;
                $userHasRule = $this->userHasRule($user,RolePublisherCreator::NAME);
            
                return ($userHasRule || $userHasSuperRule || $isAdmin);
            break;

            case Atribute::CAN_EDIT_PUBLISHER;
                $userHasRule = $this->userHasRule($user,RolePublisherEditor::NAME);

                return ($subject->isEditor($user['id']) && $userHasRule) || $userHasSuperRule || $isAdmin;
            break;
        }   
        
        return false;
    }

    private function userHasRule(array $user,string $role){
        return in_array($role, $user['roles']);
    }

}
