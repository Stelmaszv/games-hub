<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use Symfony\Component\Validator\Constraints as Assert;
use App\Generic\Api\Interfaces\DTO;

class EditorDTO implements DTO
{
    /**
    * @Assert\NotBlank
    */
   public ?int $id = null; 

   function setComponnetsData(array $componnets){}
}
