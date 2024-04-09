<?php

declare(strict_types=1);

namespace App\Controller\Publisher;
use App\Generic\Api\Controllers\GenericPostController;
use App\Validation\DTO\Publisher\PublisherWebScraberDTO;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/publisher/web-scraber/add/", name: "publisher_add", methods: ["POST"])]
class AddPublisherWebScraberController extends GenericPostController
{
    protected ?string $dto = PublisherWebScraberDTO::class;

    protected function action(): void {
    }
}
