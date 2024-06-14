<?php

namespace App\Generic\Api\Interfaces;

interface DTO
{
    /**
     * @param mixed[] $components
     */
    public function setComponentsData(array $components): void;
}
