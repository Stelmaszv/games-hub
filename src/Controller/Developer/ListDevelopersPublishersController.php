<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Entity\Publisher;
use App\Security\Atribute;
use Symfony\Component\Routing\Annotation\Route;
use App\Generic\Api\Controllers\GenericListRelationController;

#[Route("api/developer/publishers/{id}", name: "developer_publisher_list", methods: ["GET"])]
class ListDevelopersPublishersController extends GenericListRelationController
{
    protected ?string $entity = Developer::class;
    protected ?string $entityLiteration = Publisher::class;
    protected ?string $relationMethod = 'getPublisher'; 
    protected ?string $voterAtribute = Atribute::CAN_LIST_PUBLISHERS;
}

