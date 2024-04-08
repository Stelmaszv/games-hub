<?php

declare(strict_types=1);

namespace App\Entity\JsonMaper\Publisher;

use App\Generic\Components\AbstractJsonMapper;

class PublisherEditorsMapper  extends AbstractJsonMapper
{
    protected bool $multi = true ;
    public function jsonSchema(): array
    {
        return [
            "uid" => 'string',
        ];
    }

    public function defaultValue(): array
    {
        return [];
    }
}
