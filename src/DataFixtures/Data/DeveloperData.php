<?php

declare(strict_types=1);

namespace App\DataFixtures\Data;

use App\Entity\Developer;
use App\Entity\Publisher;
use App\Entity\User;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Components\AbstractDataFixture;
use App\Service\WebScraper\Developer\DescriptionsScraper;
use App\Service\WebScraper\Developer\GeneralInformationScraper;
use App\Service\WebScraper\WebScraperDescriptionFactory;
use App\Validation\DTO\Developer\DescriptionsDTO;
use App\Validation\DTO\Developer\EditorDTO;
use App\Validation\DTO\Developer\GeneralInformationDTO;

class DeveloperData extends AbstractDataFixture
{
    protected ?string $entity = Developer::class;

    protected array $data = [
        [
            'outputMessage' => 'EA_DICE',
            'createdBy' => 'devloperCreator@dot.com',
            'generalInformation' => 'https://en.wikipedia.org/wiki/Naughty_Dog',
            'descriptions' => [
                [
                    'lng' => 'pl',
                    'url' => 'https://pl.wikipedia.org/wiki/EA_DICE',
                ],
                [
                    'lng' => 'eng',
                    'url' => 'https://en.wikipedia.org/wiki/DICE_(company)',
                ],
                [
                    'lng' => 'fr',
                    'url' => 'https://fr.wikipedia.org/wiki/DICE_(studio)',
                ],
            ],
            'creationDate' => null,
            'editors' => ['devloperCreator@dot.com'],
            'verified' => true,
        ],
    ];

    public function onCreatedBySet(mixed $value, object $entity) : User
    {
        $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $value]);

        return $user;
    }

    protected function initRelations(ApiInterface $entityObj) : void
    {
        $this->addRelation('Publisher', $entityObj, $this->getPublisher('Electronic Arts'));
    }

    private function getPublisher(string $publisherName): ?ApiInterface
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('p')->from(Publisher::class, 'p');
        $query = $queryBuilder->getQuery();
        $publisher = $query->getResult();
        $elFound = null;

        foreach ($publisher as $el) {
            if ($el->getGeneralInformation()['name'] === $publisherName) {
                $elFound = $el;
            }
        }

        return $elFound;
    }

    public function onCreationDateSet(mixed $value, object $entity): \DateTime
    {
        return new \DateTime();
    }

    public function onGeneralInformationSet(mixed $value, object $entity) : GeneralInformationDTO
    {
        $generalInformationScraper = new GeneralInformationScraper($value);

        return new GeneralInformationDTO($generalInformationScraper->getData());
    }

    public function onDescriptionsSet(mixed $value, object $entity) : DescriptionsDTO
    {
        $webScraperFactory = new WebScraperDescriptionFactory(new DescriptionsScraper());
        $webScraperFactory->setDescription($value);

        return new DescriptionsDTO($webScraperFactory->getDescription());
    }

    /**
     * @return array<EditorDTO> $descriptions
     */
    public function onEditorsSet(mixed $value, object $entity) :array
    {
        $editors = [];
        
        foreach ($value as $key => $editor) {
            $editors[$key] = new EditorDTO();
            $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $editor]);
            $editors[$key]->id = $user->getId();
        }

        return $editors;
    }
}
