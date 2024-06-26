<?php

namespace App\Generic\Api\Controllers;

use App\Entity\User;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Interfaces\DTO;
use App\Generic\Api\Interfaces\GenericInterface;
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

class GenericUpdateController extends AbstractController implements GenericInterface
{
    use GenericValidation;
    use GenericProcessEntity;
    use GenericJSONResponse;
    use SecurityTrait;
    private Security $security;
    private JWT $jwt;
    private int $id;
    protected ParameterBag $attributes;
    protected ParameterBag $query;

    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ManagerRegistry $managerRegistry,
        Security $security,
        JWT $jwt,
        int $id
    ): JsonResponse {
        $this->initialize($request, $serializer, $validator, $managerRegistry, $security);
        $this->checkData();
        $this->jwt = $jwt;
        $this->attributes = $request->attributes;
        $this->query = $request->query;
        $this->setId($id);

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

        $JWTtoken = $this->jwt->getJWTFromHeader();
        $JWTtokenDecoded = $this->jwt->decode($JWTtoken);

        $user = $this->managerRegistry->getRepository(User::class)->find($JWTtokenDecoded['id']);

        $dto = new $this->dto(json_decode($data, true));

        if (!$dto instanceof DTO) {
            return $this->respondWithError('this is not instanceof DTO', JsonResponse::HTTP_BAD_REQUEST);
        }

        $dto->setComponentsData([
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

    public function getEntity(): ?ApiInterface
    {
        $entity = $this->managerRegistry->getRepository($this->entity)->find($this->id);

        if (null === $this->id) {
            return $this->respondWithError('GenericUpdateController {id} in address', JsonResponse::HTTP_NOT_FOUND);
        }

        $entity = $this->managerRegistry->getRepository($this->entity)->find($this->id);

        if (!$entity) {
            return $this->respondWithError('Object not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->managerRegistry->getRepository($this->entity)->find($this->id);
    }
}
