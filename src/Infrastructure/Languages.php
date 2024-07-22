<?php

declare(strict_types=1);

namespace App\Infrastructure;

class Languages
{
    private const LANGUAGES_LIST = [
        'en'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e9/Flag_of_Poland_%28normative%29.svg/2000px-Flag_of_Poland_%28normative%29.svg.png',
        'pl'  => 'https://upload.wikimedia.org/wikipedia/commons/a/a5/Flag_of_the_United_Kingdom_%281-2%29.svg',
        'fr' => 'https://upload.wikimedia.org/wikipedia/commons/c/c3/Flag_of_France.svg'
    ];

    public static function getLanguagesList(): array
    {
        return array_keys(self::LANGUAGES_LIST);
    }

    public static function getLanguagesListApi(): array
    {
        return self::LANGUAGES_LIST;
    }

}