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

      /**
    * @Assert\NotNull
    */
    public string $fr = '';

    function __construct(array $data = [])
    {
        $this->eng = $data['pl'] ?? '' ;
        $this->pl = $data['eng'] ?? '';
        $this->fr = $data['fr'] ?? '';
    }

    function setComponnetsData(array $componnets){}
}
