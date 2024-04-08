<?php
namespace App\Security;

use App\Roles\RoleUser;
use App\Roles\RoleSuperAdmin;
use App\Roles\RolePublisherCreator;

class Roles
{
    private const ROLES = [
        RoleSuperAdmin::NAME => RoleSuperAdmin::ROLES,
        RoleUser::NAME => RoleUser::ROLES,
        RolePublisherCreator::NAME => RolePublisherCreator::ROLES
    ];

    static function checkAtribute(string $role,$atribute){
        return in_array($atribute, self::ROLES[$role]);
    }
}