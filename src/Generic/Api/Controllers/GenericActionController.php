<?php

declare(strict_types=1);

namespace App\Generic\Api\Controllers;

use App\Generic\Auth\JWT;
use App\Generic\Api\Trait\GenericAction;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Security;
use App\Generic\Api\Trait\GenericJSONResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Generic\Api\Trait\Security as SecurityTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class GenericActionController extends AbstractController
{
    use GenericAction;
    use GenericJSONResponse;
    use SecurityTrait;
    
    protected ManagerRegistry $managerRegistry;
    protected ParameterBag $attributes;
    protected ParameterBag $query;
    private Security $security;

    public function __invoke(
        ManagerRegistry $managerRegistry,
        Request $request,
        Security $security,
        JWT $jwt,
        ): JsonResponse
    {
        $this->managerRegistry = $managerRegistry;
        $this->security = $security;
        $this->attributes = $request->attributes;
        $this->query = $request->query;

        return $this->setSecurityView('executeAction',$jwt);
    }

    private function executeAction(): JsonResponse
    {
        $this->beforeAction();
        $this->action();
        $this->afterAction();

        return $this->respondWithSuccess(JsonResponse::HTTP_OK);
    }

    protected function getRepository(string $entity): ObjectRepository
    {
        return $this->managerRegistry->getRepository($entity);
    }
}