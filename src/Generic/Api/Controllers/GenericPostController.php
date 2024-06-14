<?php

declare(strict_types=1);

namespace App\Generic\Api\Controllers;

use App\Generic\Api\Interfaces\DTO;
use App\Generic\Api\Trait\GenericAction;
use App\Generic\Api\Trait\GenericJSONResponse;
use App\Generic\Api\Trait\GenericValidation;
use App\Generic\Api\Trait\Security as SecurityTrait;
use App\Generic\Auth\JWT;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class GenericPostController extends AbstractController
{
    use GenericValidation;
    use GenericJSONResponse;
    use GenericAction;
    use SecurityTrait;

    protected ?string $dto = null;
    protected ManagerRegistry $managerRegistry;
    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;
    protected ParameterBag $attributes;
    protected ParameterBag $query;
    protected Request $request;
    protected ?JsonResponse $actionJsonData = null;
    protected JWT $jwt;

    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ManagerRegistry $managerRegistry,
        Security $security,
        JWT $jwt
    ): JsonResponse {
        $this->attributes = $request->attributes;
        $this->query = $request->query;
        $this->initialize($request, $serializer, $validator, $managerRegistry, $security, $jwt);

        return $this->setSecurityView('postAction', $jwt);
    }

    protected function initialize(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ManagerRegistry $managerRegistry,
        Security $security,
        JWT $jwt
    ): void {
        $this->jwt = $jwt;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->setSecurity($security);
        $this->managerRegistry = $managerRegistry;
        $this->request = $request;
    }

    private function postAction(): JsonResponse
    {
        $this->beforeAction();

        $data = $this->request->getContent();

        if (empty($data)) {
            return $this->respondWithError('No data provided', JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!$this->dto) {
            throw new \Exception('Dto is not define in controller '.get_class($this).'!');
        }

        $dto = $this->deserializeDto($data);

        $this->beforeValidation();
        $errors = $this->validateDto($dto);
        
        if (!empty($errors)) {
            return $this->validationErrorResponse($errors);
        }

        $this->action();
        $this->afterValidation();
        $this->afterAction();

        if ($this->actionJsonData) {
            return $this->actionJsonData;
        }

        return $this->respondWithSuccess(JsonResponse::HTTP_OK);
    }

    protected function getRepository(string $entity): ObjectRepository
    {
        return $this->managerRegistry->getRepository($entity);
    }

    // DTO trait
    protected function validationDTO(DTO $DTO): void
    {
        $DTO = $this->setDTO($DTO);
        $violations = $this->validator->validate($DTO);

        if (count($violations) > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $data = [];
                $data['path'] = $violation->getPropertyPath();
                $data['message'] = $violation->getMessage();

                $errors[] = $data;
            }

            $errorMessages = [];
            foreach ($errors as $violation) {
                $errorMessages[$violation['path']] = $violation['message'];
            }

            $this->actionJsonData = new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return array<mixed>
     */
    private function DTOComponentsData(): array
    {
        return [
            'managerRegistry' => $this->managerRegistry,
            'request' => $this->request
        ];
    }

    private function setDTO(DTO $DTO) : DTO
    {
        $DTO->setComponentsData($this->DTOComponentsData());

        return $DTO;
    }
}
