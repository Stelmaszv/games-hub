<?php

declare(strict_types=1);

namespace App\Validation\DTO\Publisher;

use App\Generic\Api\Interfaces\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class DescriptionsDTO  implements DTO
{
    /**
     * @Assert\NotNull
     */
    public string $eng = '';

    /**
    * @Assert\NotNull
    */
    public string $pl = '';
}
