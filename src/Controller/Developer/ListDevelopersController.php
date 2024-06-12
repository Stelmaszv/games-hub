<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Generic\Api\Controllers\GenericListController;
use App\Security\Atribute;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/developer/list', name: 'developer_list', methods: ['GET'])]
class ListDevelopersController extends GenericListController
{
    protected ?string $entity = Developer::class;

    protected ?string $voterAttribute = Atribute::CAN_LIST_DEVELOPERS;

    protected function onQuerySet(): array
    {
        return $this->repository->findBy(['verified' => true]);
    }
}
