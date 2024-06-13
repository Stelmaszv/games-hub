<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class EditorDTO implements DTO
{
    /**
     * @Assert\NotBlank
     */
    public ?int $id = null;

    /**
     * @param mixed[] $components
     */
    public function setComponentsData(array $components): void
    {
    }
}
