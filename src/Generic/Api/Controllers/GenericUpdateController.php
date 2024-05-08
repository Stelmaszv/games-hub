<?php

namespace App\Generic\Api\Controllers;

use App\Entity\User;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Interfaces\GenricInterface;
use App\Generic\Api\Trait\GenericJSONResponse;
use App\Generic\Api\Trait\GenericProcessEntity;
use App\Generic\Api\Trait\GenericValidation;
use App\Generic\Api\Trait\Security as SecurityTrait;
use App\Generic\Auth\JWT;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GenericUpdateController extends AbstractController implements GenricInterface
{
    use GenericValidation;
    use GenericProcessEntity;
    use GenericJSONResponse;
    use SecurityTrait;
    private Security $security;
    private JWT $jwt;
    protected ParameterBag $attributes;
    protected ParameterBag $query;

    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ManagerRegistry $managerRegistry,
        Security $security,
        JWT $jwt,
    ): JsonResponse {
        $this->initialize($request, $serializer, $validator, $managerRegistry, $security);
        $this->checkData();
        $this->jwt = $jwt;
        $this->attributes = $request->attributes;
        $this->query = $request->query;

        return $this->setSecurityView('updateAction', $jwt);
    }

    protected function initialize(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ManagerRegistry $managerRegistry,
        Security $security
    ): void {
        $this->request = $request;
        $this->security = $security;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->managerRegistry = $managerRegistry;
    }

    private function updateAction(): JsonResponse
    {
        $data = $this->request->getContent();

        if (empty($data)) {
            return $this->respondWithError('No data provided', JsonResponse::HTTP_BAD_REQUEST);
        }

        $JWTtokken = $this->jwt->getJWTFromHeader();
        $user = $this->managerRegistry->getRepository(User::class)->find($JWTtokken['id']);

        $dto = $this->deserializeDto($data);

        $dto->setComponnetsData([
            'managerRegistry' => $this->managerRegistry,
            'request' => $this->request,
            'userId' => $user->getId(),
            'edit' => true,
        ]);

        $this->beforeValidation();
        $errors = $this->validateDto($dto);

        if (!empty($errors)) {
            return $this->validationErrorResponse($errors);
        }

        $this->afterValidation();
        $this->processEntity($dto);
        $this->afterProcessEntity();

        return $this->respondWithSuccess(JsonResponse::HTTP_OK);
    }

    public function getEntity(): ApiInterface
    {
        if (null === $this->request->attributes->get('id')) {
            return $this->respondWithError('GenericUpdateController {id} in address', JsonResponse::HTTP_NOT_FOUND);
        }

        $entity = $this->managerRegistry->getRepository($this->entity)->find($this->$this->attributes->get('id'));

        if (!$entity) {
            return $this->respondWithError('Object not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return $entity;
    }
}
