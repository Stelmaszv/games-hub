<?php

namespace App\Generic\Api\Interfaces;

interface ApiInterface
{
    /**
     * @return array<mixed>
     */
    public function setApiGroup(ApiInterface $entityObject, string $objectProperty): ?array;

    public function getId(): ?int;

    public function setId(int $id): self;
}
