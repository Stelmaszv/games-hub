<?php


namespace App\Roles;

use App\Security\Atribute;

class RoleUser
{
    public const NAME = 'ROLE_USER';

	public const ROLES = [
        Atribute::CAN_SHOW_PUBLISHER,
        Atribute::CAN_SHOW_DEVELOPER,
        Atribute::CAN_LIST_PUBLISHERS,
        Atribute::CAN_LIST_DEVELOPERS,
    ];
}