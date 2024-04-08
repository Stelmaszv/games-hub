<?php

namespace App\Generic\Api\Controllers;

use App\Entity\User;
use App\Generic\Auth\JWT;
use Doctrine\Persistence\ManagerRegistry;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Trait\GenericValidation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Generic\Api\Trait\GenericJSONResponse;
use App\Generic\Api\Interfaces\GenricInterface;
use App\Generic\Api\Trait\GenericProcessEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Generic\Api\Trait\Security as SecurityTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenericUpdateController extends AbstractController implements GenricInterface
{
    use GenericValidation;
    use GenericProcessEntity;
    use GenericJSONResponse;
    use SecurityTrait;

    protected null|int|string $id;
    private Security $security;
    private JWT $jwt;

    public function __invoke(
            Request $request, 
            SerializerInterface $serializer, 
            ValidatorInterface $validator, 
            ManagerRegistry $managerRegistry,
            Security $security, 
            null|int|string $id,
            JWT $jwt,
        ): JsonResponse
    {
        $this->initialize($request, $serializer, $validator, $managerRegistry,$security,$id);
        $this->checkData();
        $this->jwt = $jwt;

        return $this->setSecurityView('updateAction',$jwt);
    }

    protected function initialize(
            Request $request, 
            SerializerInterface $serializer, 
            ValidatorInterface $validator, 
            ManagerRegistry $managerRegistry,
            Security $security, null|int|string $id
        ): void
    {
        $this->request = $request;
        $this->security = $security;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->managerRegistry = $managerRegistry;
        $this->id = $id;
    }

    private function updateAction(): JsonResponse
    {
        $data = $this->request->getContent();

        if (empty($data)) {
            return $this->respondWithError('No data provided', JsonResponse::HTTP_BAD_REQUEST);
        }

        $JWTtokken = $this->jwt->decode($this->getJWTFromHeader());
        $user = $this->managerRegistry->getRepository(User::class)->find($JWTtokken['id']);

        $dto = $this->deserializeDto($data);

        $dto->setComponnetsData([
            'managerRegistry' => $this->managerRegistry,
            'request' => $this->request,
            'userId' => $user->getId(),
            'edit' => true
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

    public function getEntity() : ApiInterface {
        return $this->managerRegistry->getRepository($this->entity)->find($this->id);
    }
}
