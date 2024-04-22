<?php

declare(strict_types=1);

namespace App\Controller\Publisher;
use App\Entity\Publisher;
use App\Validation\DTO\Publisher\PublisherDTO;
use Symfony\Component\Routing\Annotation\Route;
use App\Generic\Api\Controllers\GenericCreateController;

#[Route("api/publisher/add", name: "publisher_add", methods: ["POST"])]
class AddPublisherController extends GenericCreateController
{
    protected ?string $entity = Publisher::class;
    protected ?string $dto = PublisherDTO::class;
}
