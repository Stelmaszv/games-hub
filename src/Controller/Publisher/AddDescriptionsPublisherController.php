<?php

declare(strict_types=1);

namespace App\Controller\Publisher;

use App\Generic\Api\Controllers\GenericPostController;
use App\Security\Atribute;
use App\Service\WebScraber\Developer\DescriptionsScraper;;
use App\Validation\DTO\Publisher\PublisherWebScraberDescriptionsDTO;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/web-scraber/add/descriptions', name: 'publisher_add_descriptions', methods: ['POST'])]
class AddDescriptionsPublisherController extends GenericPostController
{
    protected ?string $dto = PublisherWebScraberDescriptionsDTO::class;
    protected ?string $voterAtribute = Atribute::CAN_ADD_PUBLISHER;

    protected function onSuccessResponseMessage(): array
    {
        $data = json_decode($this->request->getContent(), true);
        $description = $this->setDescription($data['descriptions']);

        return [
            'description' => $description->getDescription()
        ];
    }

    private function setDescription(array $descriptions): DescriptionsScraper
    {
       $publisherScraber = new DescriptionsScraper();

        foreach ($descriptions as $description) {
            $publisherScraber->addDescription($description);
        }

        return $publisherScraber;
    }
}
