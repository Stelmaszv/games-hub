<?php

declare(strict_types=1);

namespace App\Controller\Publisher\Show;

use App\Entity\Publisher;
use App\Generic\Api\Controllers\GenericDetailController;
use App\Security\Atribute;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/show/{id}', name: 'publisher_show', methods: ['GET'])]
class ShowPublisherController extends GenericDetailController
{
    protected ?string $entity = Publisher::class;
    protected ?string $voterAttribute = Atribute::CAN_SHOW_PUBLISHER;
    protected mixed $voterSubject = Publisher::class;
}
