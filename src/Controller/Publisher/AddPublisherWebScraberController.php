<?php

declare(strict_types=1);

namespace App\Controller\Publisher;
use App\Entity\Publisher;
use DateTime;
use Symfony\Component\Routing\Annotation\Route;
use App\Generic\Api\Controllers\GenericPostController;
use App\Service\WebScraber\Publisher\PublisherScraber;
use App\Validation\DTO\Publisher\GeneralInformationDTO;
use App\Validation\DTO\Publisher\PublisherWebScraberDTO;
use Symfony\Component\Uid\UuidV4;


#[Route("api/publisher/web-scraber/add/", name: "publisher_add", methods: ["POST"])]
class AddPublisherWebScraberController extends GenericPostController
{
    protected ?string $dto = PublisherWebScraberDTO::class;

    protected function action(): void {
        $data = json_decode($this->request->getContent(), true);
        $publisherScraber = new PublisherScraber($data['url']);

        $uuidObject = new UuidV4();
        $uuidString = $uuidObject->toRfc4122();
        $company = new Publisher();
        $company->setId($uuidString);
        $company->setCreationDate(new DateTime());
        $company->setVerified(false);

        $gI = new GeneralInformationDTO();
        $gI->name = $publisherScraber->getGenaralInformation()['name'];
        $company->setGeneralInformation($gI);

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($company);
        $entityManager->flush();
    }
}
