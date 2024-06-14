<?php

namespace App\Generic\Api\Controllers;

use App\Generic\Api\Trait\Security as SecurityTrait;
use App\Generic\Auth\JWT;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class GenericListController extends AbstractController
{
    use SecurityTrait;
    protected ?string $entity = null;
    protected ?string $entityLiteration = null;
    protected int $perPage = 1;
    protected ObjectRepository $repository;
    protected Request $request;
    protected ManagerRegistry $managerRegistry;
    private PaginatorInterface $paginator;
    protected ParameterBag $attributes;
    protected ParameterBag $query;
    /**
     * @var array<mixed>
     */
    private ?array $paginatorData = null;
    /**
     * @var array<string>
     */
    protected array $columns = [];

    public function __construct(
        ManagerRegistry $doctrine,
        PaginatorInterface $paginator,
        Security $security
    ) {
        $this->initialize($doctrine, $paginator, $security);
    }

    public function __invoke(
        Request $request,
        JWT $jwt,
    ): JsonResponse {
        $this->request = $request;
        $this->attributes = $request->attributes;
        $this->query = $request->query;

        return $this->setSecurityView('listAction', $jwt);
    }

    protected function initialize(
        ManagerRegistry $doctrine,
        PaginatorInterface $paginator,
        Security $security
    ): void {
        $this->managerRegistry = $doctrine;
        $this->paginator = $paginator;
        $this->setSecurity($security);
        $this->repository = $this->managerRegistry->getRepository($this->entity);
    }

    protected function beforeQuery(): void
    {
    }

    protected function afterQuery(): void
    {
    }

    protected function onQuerySet(): mixed
    {
        return $this->repository->findAll();
    }

    private function listAction(): JsonResponse
    {
        if (!$this->entity) {
            throw new \Exception('Entity is not define in controller '.get_class($this).'!');
        }

        $this->beforeQuery();
        $respane = $this->getResponse();
        $this->afterQuery();

        return new JsonResponse($respane, JsonResponse::HTTP_OK);
    }

    private function getResponse(): array
    {
        return [
            'results' => $this->prepareQuerySet($this->getQuery()),
            'paginatorData' => $this->paginatorData,
        ];
    }

    private function getQuery(): mixed
    {
        return $this->onQuerySet();
    }

    private function prepareQuerySet(mixed $query): mixed
    {
        $data = $this->setData($query);

        if ($this->perPage) {
            $paginator = $this->paginator->paginate(
                new ArrayCollection($data),
                $this->request->query->getInt('page', 1),
                $this->perPage
            );

            if(method_exists($paginator, 'getPaginationData')){
                $paginationData = $paginator->getPaginationData();
                $this->paginatorData = [
                    'totalCount' => $paginationData['totalCount'],
                    'endPage' => $paginationData['endPage'],
                    'startPage' => $paginationData['startPage'],
                    'current' => $paginationData['current'],
                    'pageCount' => $paginationData['pageCount'],
                    'previous' => $paginationData['previous'] ?? null,
                    'next' => $paginationData['next'] ?? null,
                ];
            }


        }

        return $data;
    }

    /**
     * @return  array<int<0, max>>
     */
    private function setData(mixed $query): array
    {
        $entity = (null === $this->entityLiteration) ? $this->entity : $this->entityLiteration;
        $reflection = new \ReflectionClass($entity);

        $results = [];

        foreach ($query as $el) {
            $entity = [];

            if (is_array($el)) {
                $results[] = $el;
                continue;
            }

            foreach ($reflection->getProperties() as $property) {
                if (
                    0 == count($this->columns) || (
                        in_array($property->getName(), $this->columns)
                        && in_array($property->getName(), $this->columns))
                ) {
                    $method = 'get'.ucfirst($property->getName());
                    $entity[$property->getName()] = $el->$method();
                }
            }
            $results[] = $entity;
        }

        return $results;
    }
}
