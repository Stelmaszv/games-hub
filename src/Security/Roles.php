<?php

namespace App\Security;

use App\Roles\RolePublisherCreator;
use App\Roles\RolePublisherEditor;
use App\Roles\RoleSuperAdmin;
use App\Roles\RoleUser;

class Roles
{
    private const ROLES = [
        RoleSuperAdmin::NAME => RoleSuperAdmin::ROLES,
        RoleUser::NAME => RoleUser::ROLES,
        RolePublisherCreator::NAME => RolePublisherCreator::ROLES,
        RolePublisherEditor::NAME => RolePublisherEditor::ROLES,
    ];

    public static function checkAtribute(string $role, $atribute)
    {
        return in_array($atribute, self::ROLES[$role]);
    }
}
