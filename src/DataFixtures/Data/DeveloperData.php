<?php

declare(strict_types=1);

namespace App\DataFixtures\Data;

use DateTime;
use App\Entity\User;
use App\Entity\Developer;
use App\Entity\Publisher;
use App\Validation\DTO\Developer\EditorDTO;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Service\WebScraber\Publisher\Scraper;
use App\Generic\Components\AbstractDataFixture;
use App\Validation\DTO\Developer\DescriptionsDTO;
use App\Validation\DTO\Developer\GeneralInformationDTO;
use App\Service\WebScraber\Publisher\DescriptionsScraper;

class DeveloperData extends AbstractDataFixture
{
    protected ?string $entity = Developer::class;

    protected array $data = [
      [
        'outputMessage' => 'EA_DICE',
        'generalInformation' => 'https://en.wikipedia.org/wiki/DICE_(company)',
        'descriptions' => [
          [
            'lng' => 'pl',
            'url' => 'https://pl.wikipedia.org/wiki/EA_DICE'
          ],
          [
            'lng' => 'eng',
            'url' => 'https://en.wikipedia.org/wiki/DICE_(company)'
          ],
          [
            'lng' => 'fr',
            'url' => 'https://fr.wikipedia.org/wiki/DICE_(studio)'
          ]
        ],
        'creationDate' => null,
        'editors' => ['kot123@dot.com','user@qwe.com'],
        'verified' => true 
      ]
    ];

    protected function initRelations(ApiInterface $entityObj) : void 
    {
      $this->addRelation('Publisher',$entityObj,$this->getPublisher('Electronic Arts'));
    } 

    private function getPublisher(string $publisherName) : ?ApiInterface 
    {
      $queryBuilder = $this->entityManager->createQueryBuilder();
      $queryBuilder->select('p')->from(Publisher::class, 'p');
      $query = $queryBuilder->getQuery();
      $publisher = $query->getResult();
      $elFound = null;

      foreach($publisher as  $el){
        if($el->getGeneralInformation()['name'] === $publisherName){
          $elFound = $el;
        }
      }

      return $elFound;
    }

    function onCreationDateSet(mixed $value,object $entity) : DateTime
    {
      return new DateTime();
    }

    function onGeneralInformationSet(mixed $value,object $entity){
      $publisherScraber = new Scraper($value);

      return  new GeneralInformationDTO($publisherScraber->getGeneralInformation());
    }

    function onDescriptionsSet(mixed $value,object $entity){

      $description = $this->setDescription($value);

      return  new DescriptionsDTO($description->getDescription());
    }

    private function setDescription(array $descriptions) : DescriptionsScraper
    {
      $publisherScraber = new DescriptionsScraper();

      foreach($descriptions as $description){
        $publisherScraber->addDescription($description);
      }

      return $publisherScraber;
    }

    function onEditorsSet(mixed $value,object $entity){

      foreach ($value as $key => $editor) {
          $editors[$key] = new EditorDTO();
          $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $editor]);
          $editors[$key]->id = $user->getId();
      }

      return $editors ;
    }
}
