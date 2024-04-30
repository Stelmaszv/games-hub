<?php

declare(strict_types=1);

namespace App\Controller\Publisher;

use App\Entity\Developer;
use App\Entity\Publisher;
use App\Generic\Api\Controllers\GenericListController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/publisher/developers/{id}", name: "publisher_developers_list", methods: ["GET"])]
class ListPublisherDevelopersController extends GenericListController
{
    protected ?string $entity = Publisher::class;
    protected ?string $entityLiteration = Developer::class;
    
    protected function onQuerySet(): mixed
    {
        return $this->repository->find($this->attributes->get('id'))->getDeveloper();
    }
}
