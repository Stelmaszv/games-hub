<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Generic\Api\Controllers\GenericListController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/users/list', name: 'users_list', methods: ['GET'])]
class UsersListController extends GenericListController
{
    protected ?string $entity = User::class;
}
