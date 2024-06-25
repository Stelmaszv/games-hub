<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use App\Validation\Validator\Publisher as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class GeneralInformationScraperDTO implements DTO
{
    /**
     * @CustomAssert\ScraperPublisherUrl
     */
    public ?string $url = null;

    /**
     * @param mixed[] $components
     */
    public function setComponentsData(array $components): void
    {
    }
}
