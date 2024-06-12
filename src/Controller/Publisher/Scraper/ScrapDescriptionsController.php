<?php

declare(strict_types=1);

namespace App\Controller\Publisher\Scraper;

use App\Generic\Api\Controllers\GenericPostController;
use App\Security\Atribute;
use App\Service\WebScraper\Publisher\DescriptionsScraper;
use App\Validation\DTO\Publisher\PublisherWebScraperDescriptionsDTO;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/web-scraper/add/descriptions', name: 'publisher_add_descriptions', methods: ['POST'])]
class ScrapDescriptionsController extends GenericPostController
{
    protected ?string $dto = PublisherWebScraperDescriptionsDTO::class;
    protected ?string $voterAttribute = Atribute::CAN_ADD_PUBLISHER;

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
       $publisherScraper = new DescriptionsScraper();

        foreach ($descriptions as $description) {
            if(!empty($description['url'])){
                $publisherScraper->addDescription($description);
            }
        }

        return $publisherScraper;
    }
}
