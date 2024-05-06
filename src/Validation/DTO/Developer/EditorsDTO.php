<?php
declare(strict_types=1);

namespace App\Validation\DTO\Developer;

use App\Generic\Api\Interfaces\DTO;

class EditorsDTO implements DTO
{
    /**
    * @Assert\NotBlank
    */
   public ?string $id = null; 

   function setComponnetsData(array $componnets){}
}
