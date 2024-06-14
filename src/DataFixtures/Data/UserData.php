<?php

namespace App\DataFixtures\Data;

use App\Entity\User;
use App\Generic\Components\AbstractDataFixture;
use App\Roles\RoleEditor;
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
            'email' => 'publisherCreator@dot.com',
            'roles' => [
                RoleUser::NAME,
                RolePublisherCreator::NAME,
                RolePublisherEditor::NAME,
            ],
            'password' => 'qwe',
        ],
        [
            'outputMessage' => 'Pani',
            'email' => 'publisherEditor@wp.pl',
            'roles' => [
                RoleUser::NAME,
                RolePublisherEditor::NAME,
            ],
            'password' => 'vbn',
        ],
        [
            'outputMessage' => 'Kot123',
            'email' => 'devloperCreator@dot.com',
            'roles' => [
                RoleUser::NAME,
                RoleEditor::NAME,
            ],
            'password' => 'qwe',
        ],
    ];

    public function onPasswordSet(mixed $value, object $entity): string
    {
        return $this->passwordEncoder->hashPassword(
            $entity,
            $value
        );
    }
}
