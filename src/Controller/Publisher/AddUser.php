<?php

declare(strict_types=1);

namespace App\Controller\Publisher;

use App\Entity\User;
use App\Entity\Publisher;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use App\Generic\Api\Controllers\GenericListController;
use App\Generic\Api\Controllers\GenericActionController;

/**
    * @Route("api/users", name="car_add", methods={"GET"})
*/
class AddUser extends GenericListController
{
    protected ?string $entity = Publisher::class;
    

}
