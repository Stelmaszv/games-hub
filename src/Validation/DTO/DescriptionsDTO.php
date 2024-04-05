<?php

declare(strict_types=1);

namespace App\Validation\DTO;

use App\Generic\Api\Interfaces\DTO;

class DescriptionsDTO  implements DTO
{
    public ?string $eng = null;
    public ?string $pl = null;
}
