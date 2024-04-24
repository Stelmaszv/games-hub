<?php

declare(strict_types=1);

namespace App\Validation\DTO\Developer;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class PublisherIdDTO  implements DTO
{
    /**
    * @Assert\NotBlank
    */
   public ?string $id = null; 

    public function setComponnetsData(array $componnets): void {}
}
