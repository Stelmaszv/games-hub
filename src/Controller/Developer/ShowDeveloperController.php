<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Developer;
use App\Generic\Api\Controllers\GenericDetailController;

#[Route("api/developer/show/{id}", name: "developer_show", methods: ["GET"])]
class ShowDeveloperController  extends GenericDetailController
{
    protected ?string $entity = Developer::class;
}
