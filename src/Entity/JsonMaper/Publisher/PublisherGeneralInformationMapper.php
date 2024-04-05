<?php

declare(strict_types=1);

namespace App\Entity\JsonMaper\Publisher;

use App\Generic\Components\AbstractJsonMapper;

class PublisherGeneralInformationMapper   extends AbstractJsonMapper
{
    public function jsonSchema(): array
    {
        return [
            "name" => "string",
        ];
    }

    public function defaultValue(): array
    {
        return [
            "name" => "string",
        ];
    }
}
