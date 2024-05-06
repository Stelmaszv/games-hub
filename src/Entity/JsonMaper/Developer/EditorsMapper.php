<?php

declare(strict_types=1);

namespace App\Entity\JsonMaper\Developer;

use App\Generic\Components\AbstractJsonMapper;

class EditorsMapper  extends AbstractJsonMapper
{
    protected bool $multi = true ;
    public function jsonSchema(): array
    {
        return [
            "id" => 'string',
        ];
    }

    public function defaultValue(): array
    {
        return [];
    }
}
