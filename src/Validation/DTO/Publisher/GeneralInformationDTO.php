<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class GeneralInformationDTO  implements DTO
{   
    /**
    * @Assert\NotBlank
    */
    public ?string $name = null;

    public ?string $founded = null;

    public ?string $funder = null;

    public ?string $headquarter = null;

    public ?string $origin = null;

    /**
     * @Assert\Regex(
     *     pattern="/^(https?:\/\/)?([a-zA-Z0-9]+\.)+[a-zA-Z]{2,}$/",
     *     message="Invalid website URL format."
     * )
     */
    public ?string $website = null;

    function __construct(?string $name, ?string $founded, ?string $funder, ?string $headquarter, ?string $origin, ?string $website){
        $this->name = $name;
        $this->founded = $founded;
        $this->funder = $funder;
        $this->headquarter = $headquarter;
        $this->origin = $origin;
        $this->website = $website;
    }
}
