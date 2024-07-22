<?php

declare(strict_types=1);

namespace App\Infrastructure;

class Languages
{
    private const LANGUAGES_LIST = [
        'en',
        'pl',
        'fr'
    ];

    public static function getLanguagesList(): array
    {
        return self::LANGUAGES_LIST;
    }
}
