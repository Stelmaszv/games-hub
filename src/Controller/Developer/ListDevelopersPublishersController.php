<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Entity\Publisher;
use App\Generic\Api\Controllers\GenericListController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/developer/publishers/{id}", name: "developer_publisher_list", methods: ["GET"])]
class ListDevelopersPublishersController extends GenericListController
{
    protected ?string $entity = Developer::class;
    protected ?string $entityLiteration = Publisher::class;
    
    protected function onQuerySet(): mixed
    {
        return $this->repository->find($this->attributes->get('id'))->getPublisher();
    }
}

