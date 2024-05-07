<?php

declare(strict_types=1);

namespace App\DataFixtures\Data;

use DateTime;
use App\Entity\User;
use App\Entity\Developer;
use App\Validation\DTO\Developer\EditorDTO;
use App\Generic\Components\AbstractDataFixture;
use App\Validation\DTO\Developer\DescriptionsDTO;
use App\Validation\DTO\Developer\GeneralInformationDTO;

class DeveloperData extends AbstractDataFixture
{
    protected ?string $entity = Developer::class;
    protected array $data = [
      [
        'generalInformation' => null,
        'descriptions' => null,
        'creationDate' => null,
        'editors' => [['id'=>'kot123@dot.com'],['id'=>'user@qwe.com']],
        'verified' => true 
      ]
    ];

    function onCreationDateSet(mixed $value,object $entity){
        return new DateTime();
    }

    function onGeneralInformationSet(mixed $value,object $entity){
      return  new GeneralInformationDTO([
        'name' => 'EA',
        'founded' => 'dqqf',
        'headquarter' => 'LA',
        'origin' => 'USA',
        'website' => 'www.ea.pl'
      ]);
    }

    function onDescriptionsSet(mixed $value,object $entity){
      return  new DescriptionsDTO([
        "pl" => 'fqef',
        "eng" => 'fqef',
        "fr" => 'qefeqf'
      ]);
    }

    function onEditorsSet(mixed $value,object $entity){

      foreach ($value as $key => $editor) {
          $editors[$key] = new EditorDTO();
          $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $editor['id']]);
          $editors[$key]->id = $user->getId();
      }

      return $editors ;
    }
}
