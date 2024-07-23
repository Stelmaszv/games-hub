<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Generic\Components\AbstractJsonMapper;

class DescriptionsLanguageMaper extends AbstractJsonMapper
{
    public function jsonSchema(): array
    {
        $languages  = Languages::getLanguagesKeys();
        $schema = [];
        foreach ($languages as $language) {
            $schema[$language] = 'string';
        }

        return $schema;
    }

    public function defaultValue(): array
    {
        $languages = Languages::getLanguagesKeys();
        $defaults = [];
        foreach ($languages as $language) {
            $defaults[$language] = '';
        }

        return $defaults;
    }
}
