<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Generic\Api\Controllers\GenericCreateController;
use App\Security\Atribute;
use App\Validation\DTO\Developer\DeveloperDTO;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/developer/add', name: 'developer_add', methods: ['POST'])]
class AddDeveloperController extends GenericCreateController
{
    protected ?string $entity = Developer::class;
    protected ?string $dto = DeveloperDTO::class;
    protected ?string $voterAttribute = Atribute::CAN_ADD_DEVELOPER;
}
