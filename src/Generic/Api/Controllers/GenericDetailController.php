<?php

namespace App\Generic\Api\Controllers;

use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Api\Trait\GenericJSONResponse;
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

class GenericDetailController extends AbstractController
{
    use SecurityTrait;
    use GenericJSONResponse;

    protected ?string $entity = null;
    protected ManagerRegistry $managerRegistry;
    protected ObjectRepository $repository;
    private SerializerInterface $serializer;
    protected ParameterBag $attributes;
    protected ParameterBag $query;
    protected Request $request;
    private Security $security;
    protected array $columns = [];

    public function __invoke(
        ManagerRegistry $managerRegistry,
        SerializerInterface $serializer,
        Request $request,
        Security $security,
        JWT $jwt
    ): JsonResponse {
        if (!$this->entity) {
            throw new \Exception('Entity is not define in controller '.get_class($this).'!');
        }

        $this->request = $request;
        $this->attributes = $request->attributes;
        $this->query = $request->query;
        $this->initialize($managerRegistry, $serializer, $security);

        return $this->setSecurityView('detailAction', $jwt);
    }

    protected function initialize(
        ManagerRegistry $managerRegistry,
        SerializerInterface $serializer,
        Security $security
    ): void {
        $this->managerRegistry = $managerRegistry;
        $this->serializer = $serializer;
        $this->security = $security;
        $this->repository = $this->managerRegistry->getRepository($this->entity);
    }

    protected function onQuerySet(): ?object
    {
        return $this->managerRegistry->getRepository($this->entity)->find($this->request->attributes->get('id'));
    }

    protected function beforeQuery(): void
    {
    }

    protected function afterQuery(): void
    {
    }

    private function detailAction(): JsonResponse
    {
        if (null === $this->request->attributes->get('id')) {
            return $this->respondWithError('GenericDeleteController requied {id} in address', JsonResponse::HTTP_NOT_FOUND);
        }

        $this->beforeQuery();
        $entity = $this->getObject();
        $this->afterQuery();

        if (!$entity) {
            return new JsonResponse(['message' => 'Object not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->normalize($entity), JsonResponse::HTTP_OK);
    }

    private function normalize(ApiInterface $object): array
    {
        return $this->serializer->normalize($this->setData($object), null, []);
    }

    private function getObject(): ?object
    {
        return $this->onQuerySet();
    }

    private function setData(ApiInterface $entity): array
    {
        $reflection = new \ReflectionClass($entity);

        $result = [];

        foreach ($reflection->getProperties() as $property) {
            if (0 == count($this->columns) || (in_array($property->getName(), $this->columns) && in_array($property->getName(), $this->columns))) {
                $method = 'get'.ucfirst($property->getName());
                $result[$property->getName()] = $entity->$method();
            }
        }

        return $result;
    }
}
