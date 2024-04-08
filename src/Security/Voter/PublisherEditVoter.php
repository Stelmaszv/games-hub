<?php


namespace App\Security\Voter;

use App\Entity\Publisher;
use App\Generic\Auth\JWT;
use App\Security\Atribute;
use App\Roles\RoleSuperAdmin;
use App\Roles\RolePublisherEditor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PublisherEditVoter extends Voter
{
    const EDIT = Atribute::CAN_EDIT_PUBLISHER;
    private JWT $jwtService;
    protected RequestStack $requestStack;

    function __construct(RequestStack $requestStack,JWT $jwtService){
        $this->requestStack = $requestStack;
        $this->jwtService = $jwtService;
    }
    
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT]) && $subject instanceof Publisher;
    }
    
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {    
        $user = $this->getJWTFromHeader();
        
        $userHasSuperRule = in_array(RoleSuperAdmin::NAME, $this->jwtService->decode($user)['roles']);
        $userHasRule = in_array(RolePublisherEditor::NAME, $this->jwtService->decode($user)['roles']);

        return (($this->userInEditors($subject->getEditors(),$this->jwtService->decode($user)['id']) && $userHasRule) || $userHasSuperRule);
    }

    private function userInEditors(array $editors,string $user): bool
    {
        foreach($editors as $editor){
            if($editor['uid'] === $user){
                return true;
            }
        }
        return false;
    }

    private function getJWTFromHeader(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        
        if ($request instanceof Request) {
            $authorizationHeader = $request->headers->get('Authorization');

            if (strpos($authorizationHeader, 'Bearer ') === 0) {
                return substr($authorizationHeader, 7);
            }
        }
        return null;
    }
}