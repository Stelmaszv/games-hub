<?php

declare(strict_types=1);

namespace App\Validation\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Generic\Api\Interfaces\DTO;

class EditorsDTO implements DTO
{
    /**
    * @Assert\NotBlank
    */
   public ?string $uid = null; 
}
