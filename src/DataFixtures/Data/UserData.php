<?php

namespace App\DataFixtures\Data;

use App\Entity\User;
use App\Generic\Components\AbstractDataFixture;
use App\Roles\RolePublisherCreator;
use App\Roles\RolePublisherEditor;
use App\Roles\RoleSuperAdmin;
use App\Roles\RoleUser;

class UserData extends AbstractDataFixture
{
    protected ?string $entity = User::class;
    protected array $data = [
        [
            'outputMessage' => 'User',
            'email' => 'user@qwe.com',
            'roles' => [
                RoleSuperAdmin::NAME,
                RoleUser::NAME,
            ],
            'password' => '123',
        ],
        [
            'outputMessage' => 'Kot123',
            'email' => 'kot123@dot.com',
            'roles' => [
                RoleUser::NAME,
                RolePublisherCreator::NAME,
                RolePublisherEditor::NAME,
            ],
            'password' => 'qwe',
        ],
        [
            'outputMessage' => 'Pani',
            'email' => 'pani@wp.pl',
            'roles' => [
                RoleUser::NAME,
                RolePublisherEditor::NAME,
            ],
            'password' => 'vbn',
        ],
    ];

    public function onPasswordSet(mixed $value, object $entity)
    {
        return $this->passwordEncoder->hashPassword(
            $entity,
            $value
        );
    }
}
