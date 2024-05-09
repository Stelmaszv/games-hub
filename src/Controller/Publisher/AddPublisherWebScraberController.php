<?php

declare(strict_types=1);

namespace App\Controller\Publisher;

use App\Entity\Publisher;
use App\Entity\User;
use App\Generic\Api\Controllers\GenericPostController;
use App\Security\Atribute;
use App\Service\WebScraber\Publisher\PublisherScraper;
use App\Validation\DTO\Publisher\DescriptionsDTO;
use App\Validation\DTO\Publisher\GeneralInformationDTO;
use App\Validation\DTO\Publisher\PublisherDTO;
use App\Validation\DTO\Publisher\PublisherWebScraberDTO;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

#[Route('api/publisher/web-scraber/add/', name: 'publisher_add_web_scraber', methods: ['POST'])]
class AddPublisherWebScraberController extends GenericPostController
{
    protected ?string $dto = PublisherWebScraberDTO::class;
    protected ?string $voterAtribute = Atribute::CAN_ADD_PUBLISHER;

    protected function action(): void
    {
        $publisherDTO = $this->setPublisherDTO();
        $this->validationDTO($publisherDTO);

        if (null === $this->actionJsonData) {
            $this->savePublisher($publisherDTO);
        }
    }

    private function setGenaralInformation(array $publisherGenaralInformation): GeneralInformationDTO
    {
        return new GeneralInformationDTO(
            $publisherGenaralInformation['name'] ?? null,
            $publisherGenaralInformation['founded'] ?? null,
            $publisherGenaralInformation['headquarters'] ?? null,
            $publisherGenaralInformation['origin'] ?? null,
            $publisherGenaralInformation['website'] ?? null
        );
    }

    private function setPublisherDTO(): PublisherDTO
    {
        $data = json_decode($this->request->getContent(), true);
        $publisherScraber = new PublisherScraper($data['url']);

        return new PublisherDTO(
            $this->setGenaralInformation($publisherScraber->getGeneralInformation()),
            new DescriptionsDTO('', '', ''),
            [],
        );
    }

    private function savePublisher(PublisherDTO $publisherDTO): void
    {
        $uuidObject = new UuidV4();
        $uuidString = $uuidObject->toRfc4122();

        $publisher = new Publisher();
        $publisher->setId($uuidString);
        $publisher->setCreationDate(new \DateTime());
        $publisher->setEditors($publisherDTO->editors);
        $publisher->setGeneralInformation($publisherDTO->generalInformation);
        $publisher->setDescriptions($publisherDTO->descriptions);
        $publisher->setVerified($publisherDTO->verified);
        $publisher->setCreatedBy($this->managerRegistry->getRepository(User::class)->find($publisherDTO->createdBy));

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($publisher);
        $entityManager->flush();
    }
}
