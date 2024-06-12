<?php

declare(strict_types=1);

namespace App\Validation\DTO\Developer;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class PublisherDTO implements DTO
{
    /**
     * @Assert\NotBlank
     */
    public ?int $id = null;

    public function setComponentsData(array $components): void
    {
    }
}
