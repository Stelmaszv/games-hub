<?php

declare(strict_types=1);

namespace App\Validation\DTO;

use DateTime;
use DateTimeImmutable;
use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;


class PublisherDTO implements DTO
{
    public ?string $id = null;

    /**
     * @Assert\NotNull
    */
    public ?string $createdBy = null;

    public DateTime $creationDate; 
    
    /**
     * @Assert\NotNull
     */
    public ?array $generalInformation = null;

    /**
     * @Assert\NotNull
     */
    public ?array $descriptions = null;

    public function __construct()
    {
        $this->creationDate = new DateTime();
    }
}
