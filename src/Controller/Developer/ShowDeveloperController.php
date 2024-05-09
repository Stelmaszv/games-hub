<?php

declare(strict_types=1);

namespace App\Controller\Developer;

use App\Entity\Developer;
use App\Generic\Api\Controllers\GenericDetailController;
use App\Security\Atribute;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/developer/show/{id}', name: 'developer_show', methods: ['GET'])]
class ShowDeveloperController extends GenericDetailController
{
    protected ?string $entity = Developer::class;
    protected ?string $voterAtribute = Atribute::CAN_SHOW_DEVELOPER;
}
