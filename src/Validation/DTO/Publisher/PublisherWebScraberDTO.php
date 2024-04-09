<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validation\Validator\Publisher as CustomAssert;

class PublisherWebScraberDTO  implements DTO
{
    /**
    * @Assert\NotBlank
    * @Assert\Url
    * @CustomAssert\PublisherUrl
    */
    public ?string $url = null;
}
