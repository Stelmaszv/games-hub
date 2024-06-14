<?php

namespace App\Service\WebScraper;

interface DescriptionScraperInterface
{
    /**
     * @return array<string>
     */
    public function getDescription(): array;

    /**
     * @param array<string> $description
     */
    public function addDescription(array $description): void;
}
