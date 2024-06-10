<?php

declare(strict_types=1);

namespace App\Controller\Publisher;

use App\Generic\Api\Controllers\GenericPostController;
use App\Security\Atribute;
use App\Service\WebScraber\Developer\GeneralInformationScraper;
use App\Validation\DTO\Publisher\PublisherWebScraberDTO;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/web-scraber/add/', name: 'publisher_add_web_scraber', methods: ['POST'])]
class AddPublisherWebScraberController extends GenericPostController
{
    protected ?string $dto = PublisherWebScraberDTO::class;
    protected ?string $voterAtribute = Atribute::CAN_ADD_PUBLISHER;
    
    protected function onSuccessResponseMessage(): array
    {
        return [
            'generalInformation' => $this->getGeneralInformation()
        ];
    }

    private function getGeneralInformation(): array
    {
        $data = json_decode($this->request->getContent(), true);
        $generalInformationScraper = new GeneralInformationScraper($data['url']);

        return $generalInformationScraper->getData();
    }
}
