<?php

declare(strict_types=1);

namespace App\Roles;

use App\Security\Atribute;

class RolePublisherEditor
{
    public const NAME = 'ROLE_PUBLISHER_EDITOR';

    public const ROLES = [
        Atribute::CAN_EDIT_PUBLISHER
    ];
}
