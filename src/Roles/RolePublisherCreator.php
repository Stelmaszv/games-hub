<?php

declare(strict_types=1);

namespace App\Roles;

use App\Security\Atribute;

class RolePublisherCreator
{
    public const NAME = 'ROLE_PUBLISHER_CREATOR';

    public const ROLES = [
        Atribute::CAN_ADD_PUBLISHER
    ];
}
