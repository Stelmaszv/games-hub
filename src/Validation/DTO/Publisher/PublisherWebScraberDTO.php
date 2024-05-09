<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use App\Validation\Validator\Publisher as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class PublisherWebScraberDTO implements DTO
{
    /**
     * @Assert\NotBlank
     *
     * @Assert\Url
     *
     * @CustomAssert\PublisherUrl
     */
    public ?string $url = null;

    public function setComponnetsData(array $componnets)
    {
    }
}
