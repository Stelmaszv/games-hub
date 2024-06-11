<?php

declare(strict_types=1);

namespace App\Controller\Publisher\Scraber;

use App\Security\Atribute;
use Symfony\Component\Routing\Annotation\Route;
use App\Generic\Api\Controllers\GenericPostController;
use App\Validation\DTO\Publisher\GeneralInformationScraberDTO;
use App\Service\WebScraber\Developer\GeneralInformationScraper;

#[Route('api/publisher/web-scraber/add/general-information', name: 'publisher_add_web_scraber', methods: ['POST'])]
class ScrabGeneralInformationController extends GenericPostController
{
    protected ?string $dto = GeneralInformationScraberDTO::class;
    protected ?string $voterAtribute = Atribute::CAN_ADD_PUBLISHER;
    
    protected function onSuccessResponseMessage(): array
    {
        return [
            'generalInformation' => $this->getDataFromScraper()
        ];
    }

    private function getDataFromScraper(): array
    {
        $data = json_decode($this->request->getContent(), true);
        $generalInformationScraper = new GeneralInformationScraper($data['url']);

        return $generalInformationScraper->getData();
    }
}
