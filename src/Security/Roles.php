<?php

namespace App\Security;

use App\Roles\RoleAdmin;
use App\Roles\RolePublisherCreator;
use App\Roles\RolePublisherEditor;
use App\Roles\RoleSuperAdmin;
use App\Roles\RoleUser;

class Roles
{
    private const ROLES = [
        RoleAdmin::NAME => RoleSuperAdmin::ROLES,
        RoleSuperAdmin::NAME => RoleSuperAdmin::ROLES,
        RoleUser::NAME => RoleUser::ROLES,
        RolePublisherCreator::NAME => RolePublisherCreator::ROLES,
        RolePublisherEditor::NAME => RolePublisherEditor::ROLES,
    ];

    public static function checkAttribute(string $role, string $attribute) : bool
    {
        return in_array($attribute, self::ROLES[$role]);
    }
}
