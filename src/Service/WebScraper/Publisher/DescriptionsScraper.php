<?php

declare(strict_types=1);

namespace App\Service\WebScraper\Publisher;

use App\Service\WebScraper\DescriptionScraperInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class DescriptionsScraper implements DescriptionScraperInterface
{
    private Crawler $crawler;

    /**
     * @var array<string>
     */
    private array $description;

    public function getLang(string $key): string
    {
        return $this->description[$key];
    }

    /**
     * @return array<string>
     */
    public function getDescription(): array
    {
        return $this->description;
    }

    /**
     * @param array<string> $description
     */
    public function addDescription(array $description): void
    {
        $this->setUrl($description['url']);

        $key = $description['lng'];
        $paragraphs = $this->crawler->filter('p');

        $desc = '';

        $paragraphs->each(function (Crawler $node) use (&$desc) {
            if ('' !== $node->text()) {
                $desc .= $node->text()."\n";
            }
        });

        $this->description[$key] = $desc;
    }

    private function setUrl(string $url) : void
    {
        $client = new Client([
            'base_uri' => $url,
            'timeout' => 2.0,
        ]);

        $response = $client->request('GET', '');
        $htmlContent = $response->getBody()->getContents();
        $this->crawler = new Crawler($htmlContent);
    }
}
