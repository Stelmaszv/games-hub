<?php

declare(strict_types=1);

namespace App\Validation\DTO;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class GeneralInformationDTO  implements DTO
{   
    
    /**
    * @Assert\NotBlank
    */
    public ?string $name = null;
}
