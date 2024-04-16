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

    function __construct(string $eng, string $pl, string $fr)
    {
        $this->eng = $eng;
        $this->pl = $pl;
        $this->fr = $fr;
    }

    function setComponnetsData(array $componnets){}
}
