<?php

declare(strict_types=1);

namespace App\Service\WebScraper\Publisher;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class GeneralInformationScraper
{
    private string $url;
    private Crawler $crawler;

    /**
     * @var array<string>
     */
    private array $data = [];

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->initializeCrawler();
    }

    private function initializeCrawler() : void
    {
        $client = new Client([
            'base_uri' => $this->url,
            'timeout' => 2.0,
        ]);

        $response = $client->request('GET', '');
        $htmlContent = $response->getBody()->getContents();
        $this->crawler = new Crawler($htmlContent);
    }

     /**
     * @return array<string>
     */
    public function getData() : array
    {
        $this->getName();
        $this->getFounded();
        $this->getWebsite();
        $this->getHeadquarters();

        return $this->data;
    }

    private function getName() : void
    {
        $elements = $this->crawler->filter('.mw-page-title-main');
        $elements->each(function (Crawler $node, $i) {
            $this->data['name'] = $node->text();
        });
    }

    private function getFounded() : void
    {
        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (false !== strpos($row->text(), 'Founded')) {
                $pattern = '/\((\d{4}-\d{2}-\d{2})\)/';
                if (preg_match($pattern, $row->text(), $matches)) {
                    $date = $matches[1];
                    $this->data['founded'] = $date;
                }
            }
        });
    }

    private function getWebsite() : void
    {
        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (false !== strpos($row->text(), 'Website')) {
                $this->data['website'] = str_replace('Website', '', $row->text());
            }
        });
    }

    private function getHeadquarters() : void
    {
        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (false !== strpos($row->text(), 'Headquarters')) {
                $parts = explode(',', $row->text());
                $city = trim($parts[0]);
                $origin = implode(',', array_slice($parts, 1));
                $originWithoutDigitsAndBrackets = preg_replace('/\[\d+\]/', '', $origin);

                $this->data['origin'] = $originWithoutDigitsAndBrackets;
                $this->data['headquarter'] = str_replace('Headquarters', '', $city);
            }
        });
    }
}
