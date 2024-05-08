<?php

namespace App\Generic\Api\Interfaces;

interface ApiInterface
{
    public function setApiGroup(ApiInterface $entityObject, string $objectProperty): ?array;
}
