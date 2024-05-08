<?php

namespace App\Generic\Api\Trait;

use App\Generic\Auth\JWT;
use App\Security\Roles;
use Symfony\Component\HttpFoundation\JsonResponse;

trait Security
{
    protected ?string $voterAtribute = null;
    protected ?string $voterSubject = null;

    private function setSecurityView(string $action, JWT $jwt): JsonResponse
    {
        $subject = (null !== $this->voterSubject) ? new $this->voterSubject() : null;

        if (null === $this->voterAtribute && null === $subject) {
            return $this->$action();
        }

        if ((null !== $this->voterAtribute && null !== $subject) || (null !== $this->voterAtribute && null === $subject)) {
            if (null === $jwt->getJWTFromHeader()) {
                return new JsonResponse(['success' => false, 'message' => 'token not found'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            try {
                $JWTtokken = $jwt->decode($jwt->getJWTFromHeader());
            } catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'message' => $e->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
            }

            if (property_exists($this, 'id') && null !== $this->voterSubject) {
                $subject = $this->managerRegistry->getRepository($this->voterSubject)->find($this->id);
            }

            foreach ($JWTtokken['roles'] as $role) {
                if (Roles::checkAtribute($role, $this->voterAtribute)) {
                    $vote = $this->security->isGranted($this->voterAtribute, $subject);

                    if ($vote) {
                        if (null !== $subject) {
                            return $this->$action();
                        }

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
