<?php

declare(strict_types=1);

namespace App\Controller\Publisher\Scraper;

use App\Generic\Api\Controllers\GenericPostController;
use App\Security\Atribute;
use App\Service\WebScraper\Developer\GeneralInformationScraper;
use App\Validation\DTO\Publisher\GeneralInformationScraperDTO;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/web-scraper/add/general-information', name: 'publisher_add_web_scraper', methods: ['POST'])]
class ScrapGeneralInformationController extends GenericPostController
{
    protected ?string $dto = GeneralInformationScraperDTO::class;
    protected ?string $voterAttribute = Atribute::CAN_ADD_PUBLISHER;

    protected function onSuccessResponseMessage(): array
    {
        return [
            'generalInformation' => $this->getDataFromScraper(),
        ];
    }

    private function getDataFromScraper(): array
    {
        $data = json_decode($this->request->getContent(), true);
        $generalInformationScraper = new GeneralInformationScraper($data['url']);

        return $generalInformationScraper->getData();
    }
}
