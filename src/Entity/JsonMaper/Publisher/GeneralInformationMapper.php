<?php

declare(strict_types=1);

namespace App\Entity\JsonMaper\Publisher;

use App\Generic\Components\AbstractJsonMapper;

class GeneralInformationMapper   extends AbstractJsonMapper
{
    public function jsonSchema(): array
    {
        return [
            "name" => "string",
            "founded" => "string",
            "headquarter" => "string",
            "origin" => "string",
            "website" => "string"
        ];
    }

    public function defaultValue(): array
    {
        return [
            "name" => "",
            "founded" => "",
            "headquarter" => "",
            "origin" => "",
            "website" => ""
        ];
    }
}
