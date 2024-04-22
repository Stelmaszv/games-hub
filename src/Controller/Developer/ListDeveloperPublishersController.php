<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Generic\Api\Controllers\GenericListController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/developer/list/publishers", name: "developer_list_publishers", methods: ["GET"])]
class ListDeveloperPublishersController  extends GenericListController
{
    protected ?string $entity = Developer::class;
}
