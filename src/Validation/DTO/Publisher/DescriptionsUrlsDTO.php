<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validation\Validator\Publisher as CustomAssert;

class DescriptionsUrlsDTO implements DTO
{
    /**
    * @Assert\NotBlank
    * @Assert\Url
    * @CustomAssert\PublisherUrl
    */
    public ?string $url = null;

    /**
    * @Assert\NotBlank
    */
    public ?string $lng = null;
    function setComponnetsData(array $componnets){}
}
