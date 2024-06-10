<?php

declare(strict_types=1);

namespace App\Service\WebScraber\Developer;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class GeneralInformationScraper
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
            'timeout' => 2.0,
        ]);

        $response = $client->request('GET', '');
        $htmlContent = $response->getBody()->getContents();
        $this->crawler = new Crawler($htmlContent);
    }

    public function getData()
    {
        $this->getName();
        $this->getFounded();
        $this->getWebsite();
        $this->getHeadquarters();

        return $this->data;
    }

    private function getName()
    {
        $this->data['name'] = '';

        $elements = $this->crawler->filter('.mw-page-title-main');
        $elements->each(function (Crawler $node, $i) {
            $this->data['name'] = $node->text();
        });
    }

    private function getFounded()
    {
        $this->data['founded'] = '';
        
        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (false !== strpos($row->text(), 'Founded')) {
                $string = str_replace('Founded', '', $row->text());
                $foundedDate = $this->extractFoundedDate($string);
                if (!empty($foundedDate)) {
                    $this->data['founded'] = $foundedDate;
                }
            }
        });
    }

    private function extractFoundedDate($string)
    {
        if (preg_match("/\((\d{4}-\d{2})\)/", $string, $matches)) {
            return $matches[1].'-01';
        } elseif (preg_match("/\((\d{4})\)/", $string, $matches)) {
            return $matches[1].'-01-01';
        }

        return null;
    }

    private function getWebsite()
    {
        $this->data['website'] = '';

        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (false !== strpos($row->text(), 'Website')) {
                $this->data['website'] = str_replace('Website', '', $row->text());
            }
        });
    }

    private function getHeadquarters()
    {
        $this->data['origin'] = '';
        $this->data['headquarter'] = '';

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
