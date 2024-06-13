<?php

declare(strict_types=1);

namespace App\Validation\DTO\Developer;

use App\Generic\Api\Interfaces\DTO;

class DeveloperItemDTO  implements DTO
{
    public int $id;
    public string $name;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
    }

        /**
     * @param mixed[] $components
     */
    public function setComponentsData(array $components): void
    {

    }
}
