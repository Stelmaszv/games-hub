<?php

namespace App\Roles;

use App\Security\Atribute;

class RoleSuperAdmin
{
    public const NAME = 'ROLE_SUPER_ADMIN';

    public const ROLES = [
        Atribute::CAN_ADD_PUBLISHER,
        Atribute::CAN_LIST_PUBLISHERS,
        Atribute::CAN_EDIT_PUBLISHER,
        Atribute::CAN_DELETE_PUBLISHER
    ];
}
