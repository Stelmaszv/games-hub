<?php

namespace App\Service\WebScraper;

interface DescriptionScraperInterface
{
    public function getDescription();

    public function addDescription(array $description);
}
