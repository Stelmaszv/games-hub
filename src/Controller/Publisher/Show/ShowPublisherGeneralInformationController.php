<?php

declare(strict_types=1);

namespace App\Controller\Publisher\Show;

use App\Entity\Publisher;
use App\Generic\Api\Controllers\GenericDetailController;
use App\Security\Atribute;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/publisher/show/general-information/{id}', name: 'publisher_show_general_information', methods: ['GET'])]
class ShowPublisherGeneralInformationController  extends GenericDetailController
{
    protected ?string $entity = Publisher::class;
    protected ?string $voterAttribute = Atribute::CAN_SHOW_PUBLISHER_GENERAL_INFORMATION;
    protected mixed $voterSubject = Publisher::class;

    /**
     * @var array<string>
     */
    protected array $columns = ['generalInformation'];
}
