<?php

declare(strict_types=1);

namespace App\Service\WebScraber;

class WebSraberDescriptionFactory
{
    private DescriptionScraperInterface $descriptionScraper;

    public function __construct(DescriptionScraperInterface $descriptionScraper)
    {
        $this->descriptionScraper =  $descriptionScraper;
    }

    public function setDescription(array $descriptions): void
    {
        foreach ($descriptions as $description) {
            $this->descriptionScraper->addDescription($description);
        }
    }

    public function getDescription() : array
    {
        return $this->descriptionScraper->getDescription();
    }
}
