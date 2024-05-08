<?php

declare(strict_types=1);

namespace App\Service\WebScraber\Publisher;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{
    private string $url;
    private Crawler $crawler;
    private array $data = [];

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->initializeCrawler();
    }

    private function initializeCrawler()
    {
        $client = new Client([
            'base_uri' => $this->url,
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', '');
        $htmlContent = $response->getBody()->getContents();
        $this->crawler = new Crawler($htmlContent);
    }

    public function getGeneralInformation()
    {
        $this->getName();
        $this->getFounded();
        $this->getWebsite();
        $this->getHeadquarters();

        return $this->data['general-information'];
    }

    private function getName()
    {
        $elements = $this->crawler->filter('.mw-page-title-main');
        $elements->each(function (Crawler $node, $i) {
            $this->data['general-information']['name'] = $node->text();
        });
    }

    private function getFounded()
    {
        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (strpos($row->text(), 'Founded') !== false) {
                $pattern = '/\((\d{4}-\d{2}-\d{2})\)/';
                if (preg_match($pattern, $row->text(), $matches)) {
                    $date = $matches[1];
                    $this->data['general-information']['founded'] = $date;
                }
            }
        });
    }

    private function getWebsite()
    {
        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (strpos($row->text(), 'Website') !== false) {
                $this->data['general-information']['website'] = str_replace("Website", "", $row->text());
            }
        });
    }

    private function getHeadquarters()
    {
        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (strpos($row->text(), 'Headquarters') !== false) {
                $parts = explode(',', $row->text());
                $city = trim($parts[0]);
                $origin = implode(',', array_slice($parts, 1));
                $originWithoutDigitsAndBrackets = preg_replace('/\[\d+\]/', '', $origin);

                $this->data['general-information']['origin'] = $originWithoutDigitsAndBrackets;
                $this->data['general-information']['headquarters'] = str_replace("Headquarters", "", $city);
            }
        });
    }
}