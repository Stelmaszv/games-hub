<?php

namespace App\Generic\Api\Controllers;

use App\Generic\Api\Trait\GenericJSONResponse;
use App\Generic\Api\Trait\Security as SecurityTrait;
use App\Generic\Auth\JWT;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class GenericDeleteController extends AbstractController
{
    use SecurityTrait;
    use GenericJSONResponse;

    protected ?string $entity = null;
    private Security $security;
    protected ManagerRegistry $managerRegistry;
    protected Request $request;
    protected ParameterBag $attributes;
    protected ParameterBag $query;

    public function __invoke(
        Request $request,
        ManagerRegistry $doctrine,
        Security $security,
        JWT $jwt
    ): JsonResponse {
        $this->initialize($doctrine, $security);
        $this->attributes = $request->attributes;
        $this->query = $request->query;

        return $this->setSecurityView('deleteAction', $jwt);
    }

    private function deleteAction(): JsonResponse
    {
        if (null === $this->request->attributes->get('id')) {
            return $this->respondWithError('GenericDeleteController requied {id} in address', JsonResponse::HTTP_NOT_FOUND);
        }

        $entity = $this->managerRegistry->getRepository($this->entity)->find($this->request->attributes->get('id'));

        if (!$entity) {
            return $this->respondWithError('Object not found', JsonResponse::HTTP_NOT_FOUND);
        }

        $this->beforeDelete();
        $this->delete($entity);
        $this->afterDelete();

        return $this->respondWithSuccess(JsonResponse::HTTP_OK);
    }

    protected function initialize(
        ManagerRegistry $doctrine,
        Security $security,
    ): void {
        $this->managerRegistry = $doctrine;
        $this->security = $security;
    }

    protected function beforeDelete(): void
    {
    }

    protected function afterDelete(): void
    {
    }

    private function delete(object $car): void
    {
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->remove($car);
        $entityManager->flush();
    }
}
