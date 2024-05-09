<?php

declare(strict_types=1);

namespace App\Validation\DTO\Developer;

use App\Generic\Api\Interfaces\DTO;

class GeneralInformationDTO implements DTO
{
    /**
     * @Assert\NotBlank
     */
    public ?string $name = null;

    public string $founded = '';

    public string $headquarter = '';

    public string $origin = '';

    /**
     * @CustomAssert\Url
     *
     * @Assert\NotBlank
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

    public function setComponnetsData(array $componnets)
    {
    }
}
