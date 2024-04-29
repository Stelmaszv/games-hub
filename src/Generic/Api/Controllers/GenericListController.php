<?php

namespace App\Generic\Api\Controllers;

use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;
use App\Generic\Auth\JWT;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Generic\Api\Trait\Security as SecurityTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class GenericListController extends AbstractController
{
    use SecurityTrait;
    protected ?string $entity = null;
    protected int $perPage = 0;
    protected ObjectRepository $repository;
    protected Request $request;
    protected ManagerRegistry $managerRegistry;
    private SerializerInterface $serializer;
    private PaginatorInterface $paginator;
    private ?array $paginatorData = null;
    protected array $columns = [];
    private Security $security;

    public function __construct(
        ManagerRegistry $doctrine,
        SerializerInterface $serializer, 
        PaginatorInterface $paginator,
        Security $security
        )
    {
        $this->initialize($doctrine, $serializer, $paginator,$security);
    }

    public function __invoke(
        Request $request,
        JWT $jwt
        ): JsonResponse
    {
        $this->request = $request;

        return $this->setSecurityView('listAction',$jwt);
    }

    protected function initialize(
        ManagerRegistry $doctrine, 
        SerializerInterface $serializer, 
        PaginatorInterface $paginator,
        Security $security
        ): void
    {
        $this->managerRegistry = $doctrine;
        $this->serializer = $serializer;
        $this->paginator = $paginator;
        $this->security = $security;
        $this->repository = $this->managerRegistry->getRepository($this->entity);
    }

    protected function beforeQuery() :void {}

    protected function afterQuery() :void {}

    protected function onQuerySet(): mixed
    {
        return $this->repository->findAll();
    }

    private function listAction(): JsonResponse
    {
        if(!$this->entity) {
            throw new \Exception("Entity is not define in controller ".get_class($this)."!");
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
            'paginatorData' => $this->paginatorData
        ];
    }

    private function getQuery(): mixed
    {
        return $this->onQuerySet();
    }

    private function prepareQuerySet(mixed $query): mixed
    {
        $data = $this->setData($query);

        if($this->perPage){
            $paginator = $this->paginator->paginate(
                new ArrayCollection($data),
                $this->request->query->getInt('page', 1),
                $this->perPage
            );

            $paginationData = $paginator->getPaginationData();

            $this->paginatorData = [
                'totalCount' => $paginationData['totalCount'],
                'endPage' => $paginationData['endPage'],
                'startPage' => $paginationData['startPage'],
                'current' => $paginationData['current'],
                'pageCount' => $paginationData['pageCount'],
                'previous' => $paginationData['previous'] ?? null,
                'next' => $paginationData['next'] ?? null
            ];

            return $paginator;
        }

        return $data;
    }

    private function setData(mixed $query) : array
    {
        $reflection = new ReflectionClass($this->entity);
        
        $results = [];

        foreach ($query as $currency) {
            $entity = [];

            if(is_array($currency)){
                $results[] = $currency;
                continue;
            }

            
            foreach($reflection->getProperties() as $property){
                if(
                    count($this->columns) == 0 || (
                        in_array($property->getName() ,$this->columns) && 
                        in_array($property->getName() ,$this->columns)) 
                    ){

                    $method = 'get' . ucfirst($property->getName());             
                    $entity[$property->getName()] = $currency->$method();
                }
            }
            $results[] = $entity;
        }
        
        return $results;
    }

}