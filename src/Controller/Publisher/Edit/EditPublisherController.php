<?php

declare(strict_types=1);

namespace App\Controller\Publisher\Edit;

use App\Entity\Publisher;
use App\Generic\Api\Controllers\GenericUpdateController;
use App\Security\Atribute;
use App\Validation\DTO\Publisher\PublisherDTO;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/edit/{id}', name: 'publisher_edit', methods: ['PUT'])]
class EditPublisherController extends GenericUpdateController
{
    protected ?string $entity = Publisher::class;
    protected ?string $dto = PublisherDTO::class;
    protected ?string $voterAttribute = Atribute::CAN_EDIT_PUBLISHER;
    protected mixed $voterSubject = Publisher::class;
}
