<?php
namespace App\Generic\Api\Trait;

use App\Entity\Publisher;
use App\Security\Roles;
use App\Generic\Auth\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;

trait Security
{
    protected ?string $voterAtribute = null;
    protected ?string $voterSubject = null;
    
    private function setSecurityView(string $action,JWT $jwt): JsonResponse
    {
        $subject = ($this->voterSubject !== null) ? new $this->voterSubject() : null;

        if(null === $this->voterAtribute && $subject === null){
            return $this->$action();
        }
        
        if((null !== $this->voterAtribute && $subject !== null) || (null !== $this->voterAtribute && $subject === null)){
            
            if($this->getJWTFromHeader() === null){
                return new JsonResponse(['success' => false,"message" => 'token not found'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            try {
                $JWTtokken = $jwt->decode($this->getJWTFromHeader());
            } catch (\Exception $e) {
                return new JsonResponse(['success' => false,"message" => $e->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $subject = $this->managerRegistry->getRepository($this->entity)->find($this->id);
 
            foreach($JWTtokken['roles'] as $role){
                if(Roles::checkAtribute($role,$this->voterAtribute)){
                    $vote = $this->security->isGranted($this->voterAtribute, $subject);
                    if($vote){
                        if($subject !== null){
                            return $this->$action();
                        }

                        return $this->$action();
                    }
                }
            }
        }

        return $this->response();
    }

    private function response() :JsonResponse
    {
        return new JsonResponse(['success' => false,"message" => 'Access Denied'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    private function getJWTFromHeader(): ?string
    {
        $authorizationHeader = $this->request->headers->get('Authorization');
        if (strpos($authorizationHeader, 'Bearer ') === 0) {
            return substr($authorizationHeader, 7);
        }
        return null;
    }
}