<?php

namespace App\Service\WebScraber;

interface DescriptionScraperInterface
{
    public function getDescription();

    public function addDescription(array $description);
}
