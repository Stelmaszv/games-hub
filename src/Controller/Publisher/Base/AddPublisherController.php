<?php

declare(strict_types=1);

namespace App\Controller\Publisher\Base;

use App\Entity\Publisher;
use App\Generic\Api\Controllers\GenericCreateController;
use App\Security\Atribute;
use App\Validation\DTO\Publisher\PublisherDTO;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/add', name: 'publisher_add', methods: ['POST'])]
class AddPublisherController extends GenericCreateController
{
    protected ?string $entity = Publisher::class;
    protected ?string $dto = PublisherDTO::class;
    protected ?string $voterAtribute = Atribute::CAN_ADD_PUBLISHER;
}
