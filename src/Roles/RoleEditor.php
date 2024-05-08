<?php

declare(strict_types=1);

namespace App\Roles;

use App\Security\Atribute;

class RoleEditor
{
    public const NAME = 'ROLE_EDITOR';

    public const ROLES = [
        Atribute::CAN_ADD_DEVELOPER,
        Atribute::CAN_EDIT_DEVELOPER.
        Atribute::CAN_DELETE_DEVELOPER,
    ];
}
