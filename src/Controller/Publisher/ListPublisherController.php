<?php

declare(strict_types=1);

namespace App\Controller\Publisher;

use App\Entity\Publisher;
use App\Generic\Api\Controllers\GenericListController;
use App\Security\Atribute;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/list', name: 'publishers_list', methods: ['GET'])]
class ListPublisherController extends GenericListController
{
    protected ?string $entity = Publisher::class;

    protected ?string $voterAtribute = Atribute::CAN_LIST_PUBLISHERS;

    protected function onQuerySet(): array
    {
        return $this->repository->findBy(['verified' => true]);
    }
}
