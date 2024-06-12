<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use App\Validation\Validator\Publisher as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class GeneralInformationScraperDTO implements DTO
{
    /**
     * @Assert\NotBlank(message="emptyField")
     *
     * @Assert\Url(message="invalidUrl")
     *
     * @CustomAssert\PublisherUrl(message="invalidScraperUrl")
     */
    public ?string $url = null;

    public function setComponentsData(array $components): void {}
}
