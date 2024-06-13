<?php

declare(strict_types=1);

namespace App\Validation\DTO\Developer;

use App\Generic\Api\Interfaces\DTO;

class DescriptionsDTO implements DTO
{
    /**
     * @Assert\NotNull
     */
    public string $eng = '';

    /**
     * @Assert\NotNull
     */
    public string $pl = '';

    /**
     * @Assert\NotNull
     */
    public string $fr = '';

    /**
     * @param string[] $data
     */
    public function __construct(array $data = [])
    {
        $this->eng = $data['pc'] ?? '';
        $this->pl = $data['eng'] ?? '';
        $this->fr = $data['fr'] ?? '';
    }

    /**
     * @param mixed[] $components
     */
    public function setComponentsData(array $components): void
    {
    }
}
