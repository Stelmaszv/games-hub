<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Roles\RoleAdmin;
use App\Entity\Publisher;
use App\Generic\Auth\JWT;
use App\Roles\RolePublisherCreator;
use App\Security\Atribute;
use App\Roles\RoleSuperAdmin;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PublisherCreateVoter  extends Voter
{
    public const ADD = Atribute::CAN_ADD_PUBLISHER;
    private JWT $jwtService;
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack, JWT $jwtService)
    {
        $this->requestStack = $requestStack;
        $this->jwtService = $jwtService;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::ADD]) && $subject instanceof Publisher;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->jwtService->decode($this->jwtService->getJWTFromHeader());

        $userHasSuperRule = in_array(RoleSuperAdmin::NAME, $user['roles']);
        $userHasRule = in_array(RolePublisherCreator::NAME, $user['roles']);
        $isAdmin = \in_array(RoleAdmin::NAME, $user['roles'], true);

        return ($userHasRule || $userHasSuperRule || $isAdmin);
    }
}
