<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validation\Validator\Cumman as CustomAssert;

class GeneralInformationDTO  implements DTO
{   
    /**
    * @Assert\NotBlank
    */
    public ?string $name = null;

    public ?string $founded = null;

    public ?string $headquarter = null;

    public ?string $origin = null;

     /**
     * @CustomAssert\Url
     */
    public ?string $website = null;

    function __construct(?string $name, ?string $founded, ?string $headquarter, ?string $origin, ?string $website){
        $this->name = $name;
        $this->founded = $founded;
        $this->headquarter = $headquarter;
        $this->origin = $origin;
        $this->website = $website;
    }
}
