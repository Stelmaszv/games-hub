<?php

namespace App\Generic\Api\Identifier\Interfaces;

interface IdentifierId
{

    public function getPassword(): string;

    public function getId(): ?int;

    public function setId(int $id): self;

    public function getEmail(): ?string;

    public function setEmail(string $email): self;

        /**
     * @see UserInterface
     * @return array<mixed>
     */
    public function getRoles(): array;

    /**
     * @param array<mixed> $roles
     */
    public function setRoles(array $roles): self;
}
