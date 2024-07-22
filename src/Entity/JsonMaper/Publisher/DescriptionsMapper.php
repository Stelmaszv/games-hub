<?php

declare(strict_types=1);

namespace App\Entity\JsonMaper\Publisher;

use App\Infrastructure\Languages;
use App\Generic\Components\AbstractJsonMapper;

class DescriptionsMapper extends AbstractJsonMapper
{
    public function jsonSchema(): array
    {
        $languages = Languages::getLanguagesList();
        $schema = [];
        foreach ($languages as $language) {
            $schema[$language] = 'string';
        }
        
        return $schema;
    }

    public function defaultValue(): array
    {
        $languages = Languages::getLanguagesList();
        $defaults = [];
        foreach ($languages as $language) {
            $defaults[$language] = '';
        }

        return $defaults;
    }
}
