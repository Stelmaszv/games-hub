<?php

declare(strict_types=1);

namespace App\Controller\Publisher;
use App\Entity\Publisher;
use App\Security\Atribute;
use Symfony\Component\Routing\Annotation\Route;
use App\Generic\Api\Controllers\GenericDeleteController;

#[Route("api/publisher/delete/{id}", name: "publisher_delete", methods: ["DELETE"])]
class DeletePublisherController extends GenericDeleteController
{
    protected ?string $entity = Publisher::class;

    protected ?string $voterAtribute = Atribute::CAN_DELETE_PUBLISHER;
}
