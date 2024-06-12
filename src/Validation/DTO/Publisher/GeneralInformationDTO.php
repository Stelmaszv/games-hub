<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use App\Validation\Validator\Cumman as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class GeneralInformationDTO implements DTO
{
    /**
     * @Assert\NotBlank(message="emptyField")
     */
    public ?string $name = null;

    public string $founded = '';

    public string $headquarter = '';

    public string $origin = '';

    /**
     * @Assert\NotBlank(message="emptyField")
     */
    public ?string $website = '';

    public function __construct(array $data = [])
    {
        $this->name = isset($data['name']) ? $data['name'] : $this->name;
        $this->founded = isset($data['founded']) ? $data['founded'] : $this->founded;
        $this->headquarter = isset($data['headquarter']) ? $data['headquarter'] : $this->headquarter;
        $this->origin = isset($data['origin']) ? $data['origin'] : $this->origin;
        $this->website = isset($data['website']) ? $data['website'] : $this->website;
    }

    public function setComponentsData(array $components): void{}
}
