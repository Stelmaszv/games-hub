<?php

declare(strict_types=1);

namespace App\Controller\Publisher;

use App\Entity\Publisher;
use App\Security\Atribute;
use App\Validation\DTO\Publisher\PublisherDTO;
use Symfony\Component\Routing\Annotation\Route;
use App\Generic\Api\Controllers\GenericUpdateController;

#[Route("api/publisher/edit/{id}", name: "publisher_edit", methods: ["PUT"])]
class EditPublisherController extends GenericUpdateController
{
    protected ?string $entity = Publisher::class;
    protected ?string $dto = PublisherDTO::class;
    protected ?string $voterAtribute = Atribute::CAN_EDIT_PUBLISHER;
}
