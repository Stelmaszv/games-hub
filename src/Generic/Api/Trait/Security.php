<?php

namespace App\Generic\Api\Trait;

use App\Security\Roles;
use App\Generic\Auth\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;

trait Security
{
    protected ?string $voterAttribute = null;
    protected mixed $voterSubject = null;
    private bool $access = false; 

    private function setSecurityView(string $action, JWT $jwt): JsonResponse
    {
        $subject = null;

        if($this->voterSubject != null){
    
            if(is_string($this->voterSubject)){ 
                $subject = new $this->voterSubject();
            }

            if(is_object($this->voterSubject)){ 
                $subject = $this->voterSubject;
            }
        }

        if (null === $this->voterAttribute && null === $subject) {
            return $this->$action();
        }

        if ((null !== $this->voterAttribute && null !== $subject) || (null !== $this->voterAttribute && null === $subject)) {
            if (null === $jwt->getJWTFromHeader()) {
                return new JsonResponse(['success' => false, 'message' => 'token not found'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            try {
                $JWTtokken = $jwt->decode($jwt->getJWTFromHeader());
            } catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'message' => $e->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
            }
            
            if(property_exists($this, 'id')){
                if ($this->id !== null && null !== $this->voterSubject) {
                    $subject = $this->managerRegistry->getRepository($this->voterSubject)->find($this->id);
                }
            }

            foreach ($JWTtokken['roles'] as $role) {
                if (Roles::checkAtribute($role, $this->voterAttribute)) {
                    $vote = $this->security->isGranted($this->voterAttribute, $subject);

                    if ($vote) {
                      if (null !== $subject) {
                        $this->access = true;
                        return $this->$action();
                      }

                      $this->access = true;
                      return $this->$action();
                    }
                }
            }
        }

        return $this->response();
    }

    private function response(): JsonResponse
    {
        return new JsonResponse(['success' => false, 'message' => 'Access Denied'], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
