<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Generic\Api\Controllers\GenericListController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/developer/list", name: "developer_list", methods: ["GET"])]
class ListDevelopersController extends GenericListController
{
    protected ?string $entity = Developer::class;

    protected function onQuerySet(): array
    {
        return $this->repository->findBy(['verified' => true]);
    }
}
