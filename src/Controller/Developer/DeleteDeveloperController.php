<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Generic\Api\Controllers\GenericDeleteController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/devloper/delete/{id}", name: "developer_delete", methods: ["DELETE"])]
class DeleteDeveloperController extends GenericDeleteController
{
    protected ?string $entity = Developer::class;
}
