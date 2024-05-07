<?php

declare(strict_types=1);

namespace App\DataFixtures\Data;

use DateTime;
use App\Entity\User;
use App\Entity\Publisher;
use App\Validation\DTO\Publisher\EditorDTO;
use App\Generic\Components\AbstractDataFixture;
use App\Validation\DTO\Publisher\DescriptionsDTO;
use App\Service\WebScraber\Publisher\PublisherScraper;
use App\Validation\DTO\Publisher\GeneralInformationDTO;
use App\Service\WebScraber\Publisher\PublisherDescriptionsScraper;

class PublisherData extends AbstractDataFixture
{
    protected ?string $entity = Publisher::class;
    protected array $data = [
      [
        'createdBy' => 'pani@wp.pl',
        'generalInformation' => null,
        'descriptions' => null,
        'creationDate' => null,
        'editors' => ['kot123@dot.com','user@qwe.com'],
        'verified' => true 
      ]
    ];

    function onCreatedBySet(mixed $value,object $entity){
      $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $value]);
      return $user;
    }

    function onCreationDateSet(mixed $value,object $entity){
      return new DateTime();
    }

    function onGeneralInformationSet(mixed $value,object $entity){
      $publisherScraber = new PublisherScraper('https://en.wikipedia.org/wiki/Electronic_Arts');

      return  new GeneralInformationDTO($publisherScraber->getGeneralInformation());
    }

    function onDescriptionsSet(mixed $value,object $entity){

      $description = $this->setDescription([
        [
          'lng' => 'pl',
          'url' => 'https://pl.wikipedia.org/wiki/Electronic_Arts'
        ],
        [
          'lng' => 'eng',
          'url' => 'https://en.wikipedia.org/wiki/Electronic_Arts'
        ],
        [
          'lng' => 'fr',
          'url' => 'https://fr.wikipedia.org/wiki/Electronic_Arts'
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

    function onEditorsSet(mixed $value,object $entity){

      foreach ($value as $key => $editor) {
          $editors[$key] = new EditorDTO();
          $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $editor]);
          $editors[$key]->id = $user->getId();
      }

      return $editors ;
    }

}
