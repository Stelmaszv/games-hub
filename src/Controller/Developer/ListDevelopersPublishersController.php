<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Entity\Publisher;
use App\Generic\Api\Controllers\GenericListRelationController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/developer/publishers/{id}", name: "developer_publisher_list", methods: ["GET"])]
class ListDevelopersPublishersController extends GenericListRelationController
{
    protected ?string $entity = Developer::class;
    protected ?string $entityLiteration = Publisher::class;

    protected ?string $relationMethod = 'getPublisher'; 
}

