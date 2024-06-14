<?php

namespace App\Generic\Web\Controllers;

use App\Generic\Web\Trait\GenericGetTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenericListController extends AbstractController
{
    use GenericGetTrait;

    /**
     * @var array<string, mixed>
     */
    private ?array $paginatorData = null;
    private bool $paginate = false;
    protected ?int $perPage = null;
    protected Request $request;
    protected EntityManagerInterface $entityManager;
    protected PaginatorInterface $paginator;

    /** @var EntityRepository<object> */
    protected EntityRepository $repository;

    public function __invoke(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $this->initialize($request, $entityManager, $paginator);

        return $this->listAction();
    }

    protected function initialize(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): void
    {
        $this->checkData();
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->repository = $this->entityManager->getRepository($this->entity);
        $this->paginate = (null !== $this->perPage && 0 !== $this->perPage);
    }

    protected function onQuerySet(): mixed
    {
        return $this->repository->findAll();
    }

    private function getQuery(): mixed
    {
        $this->beforeQuery();
        $queryBuilder = $this->onQuerySet();
        /**
         * @var SlidingPaginationInterface
         */
        $pagination = $this->paginator->paginate($queryBuilder, $this->request->query->getInt('page', 1), $this->perPage);
        $this->afterQuery();
        $this->paginatorData = $pagination->getPaginationData();

        return (null !== $this->perPage && 0 !== $this->perPage) ? $pagination : $queryBuilder;
    }

    /**
     * @return array<mixed>
     */
    private function getAttributes(): array
    {
        $attributes['object'] = $this->getQuery();
        $attributes['paginate'] = $this->paginate;
        $attributes['paginatorData'] = $this->paginatorData;

        return array_merge($attributes, $this->onSetAttribute());
    }

    private function listAction(): Response
    {
        return $this->render($this->twig, $this->getAttributes());
    }
}
