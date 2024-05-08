<?php

namespace App\Security\Voter;

use App\Entity\Publisher;
use App\Generic\Auth\JWT;
use App\Roles\RoleAdmin;
use App\Roles\RolePublisherEditor;
use App\Roles\RoleSuperAdmin;
use App\Security\Atribute;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PublisherEditVoter extends Voter
{
    public const EDIT = Atribute::CAN_EDIT_PUBLISHER;
    private JWT $jwtService;
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack, JWT $jwtService)
    {
        $this->requestStack = $requestStack;
        $this->jwtService = $jwtService;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT]) && $subject instanceof Publisher;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->jwtService->decode($this->jwtService->getJWTFromHeader());

        $userHasSuperRule = in_array(RoleSuperAdmin::NAME, $user['roles']);
        $userHasRule = in_array(RolePublisherEditor::NAME, ['roles']);
        $isAdmin = \in_array(RoleAdmin::NAME, $this->jwtService->decode($user)['roles'], true);

        return ($subject->isEditor($this->jwtService->decode($user)['id']) && $userHasRule) || $userHasSuperRule || $isAdmin;
    }
}
