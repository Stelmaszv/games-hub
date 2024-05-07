<?php

declare(strict_types=1);

namespace App\DataFixtures\Data;

use DateTime;
use App\Entity\User;
use App\Entity\Developer;
use App\Entity\Publisher;
use App\Validation\DTO\Developer\EditorDTO;
use App\Generic\Api\Interfaces\ApiInterface;
use App\Generic\Components\AbstractDataFixture;
use App\Validation\DTO\Developer\DescriptionsDTO;
use App\Service\WebScraber\Publisher\PublisherScraper;
use App\Validation\DTO\Developer\GeneralInformationDTO;
use App\Service\WebScraber\Publisher\PublisherDescriptionsScraper;

class DeveloperData extends AbstractDataFixture
{
    protected ?string $entity = Developer::class;

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
        if($el->getGeneralInformation()['name']=== $publisherName){
          $elFound = $el;
        }
      }

      return $elFound;
    }

    protected array $data = [
      [
        'generalInformation' => null,
        'descriptions' => null,
        'creationDate' => null,
        'editors' => [
          ['id'=>'kot123@dot.com'],
          ['id'=>'user@qwe.com']
        ],
        'verified' => true 
      ]
    ];

    function onCreationDateSet(mixed $value,object $entity) : DateTime
    {
      return new DateTime();
    }

    function onGeneralInformationSet(mixed $value,object $entity){
      $publisherScraber = new PublisherScraper('https://en.wikipedia.org/wiki/DICE_(company)');

      return  new GeneralInformationDTO($publisherScraber->getGeneralInformation());
    }

    function onDescriptionsSet(mixed $value,object $entity){

      $description = $this->setDescription([
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
      ]);

      return  new DescriptionsDTO([
        "pl" => $description->getDescription()['pl'],
        "eng" => $description->getDescription()['eng'],
        "fr" => $description->getDescription()['fr']
      ]);
    }

    private function setDescription(array $descriptions) : PublisherDescriptionsScraper
    {
        $publisherScraber = new PublisherDescriptionsScraper();

        foreach($descriptions as $description){
            $publisherScraber->addDescription($description);
        }

        return $publisherScraber;
    }

    function onEditorsSet(mixed $value,object $entity) : array 
    {

      foreach ($value as $key => $editor) {
          $editors[$key] = new EditorDTO();
          $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $editor['id']]);
          $editors[$key]->id = $user->getId();
      }

      return $editors;
    }
}
