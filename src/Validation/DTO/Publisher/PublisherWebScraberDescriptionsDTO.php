<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class PublisherWebScraberDescriptionsDTO implements DTO
{
    /**
     * @var DescriptionsUrlsDTO[]
     * @Assert\Valid
     * @Assert\NotNull
     * @Assert\Valid()
     */
    public array $descriptions = [];

    function setComponnetsData(array $componnets){}

    public function __construct(
        array $descriptions = [],
    )
    {
        $this->descriptions = $descriptions;

        foreach ($descriptions as $key => $description) {
            $this->descriptions[$key] = new DescriptionsUrlsDTO();
            $this->descriptions[$key]->url = $description['url'];
            $this->descriptions[$key]->lng = $description['lng'];
        }
    }
}
