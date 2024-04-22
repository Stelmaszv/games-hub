<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Validation\DTO\Developer\DeveloperDTO;
use App\Generic\Api\Controllers\GenericCreateController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/developer/add", name: "developer_add", methods: ["POST"])]
class AddDeveloperController  extends GenericCreateController
{
    protected ?string $entity = Developer::class;
    protected ?string $dto = DeveloperDTO::class;
}
