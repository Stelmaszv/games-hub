<?php


namespace App\Controller\Publisher\Edit;

use App\Entity\Publisher;
use App\Security\Atribute;
use App\Validation\DTO\Publisher\PublisherDTO;
use Symfony\Component\Routing\Annotation\Route;
use App\Generic\Api\Controllers\GenericUpdateController;

#[Route('api/publisher/edit/general-information/{id}', name: 'publisher_edit_general_information', methods: ['PUT'])]
class EditPublisherGeneralInformationController extends GenericUpdateController
{
    protected ?string $entity = Publisher::class;
    protected ?string $dto = PublisherDTO::class;
    protected ?string $voterAttribute = Atribute::CAN_EDIT_PUBLISHER_GENERAL_INFORMATION;
    protected mixed $voterSubject = Publisher::class;
}