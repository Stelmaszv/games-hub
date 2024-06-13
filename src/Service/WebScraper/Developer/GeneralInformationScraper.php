<?php

declare(strict_types=1);

namespace App\Service\WebScraper\Developer;

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
        $this->data['name'] = '';

        $elements = $this->crawler->filter('.mw-page-title-main');
        $elements->each(function (Crawler $node, $i) {
            $this->data['name'] = $node->text();
        });
    }

    private function getFounded() : void
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

    private function extractFoundedDate(string $string) : ?string
    {
        if (preg_match("/\((\d{4}-\d{2})\)/", $string, $matches)) {
            return $matches[1].'-01';
        } elseif (preg_match("/\((\d{4})\)/", $string, $matches)) {
            return $matches[1].'-01-01';
        }

        return null;
    }

    private function getWebsite() : void
    {
        $this->data['website'] = '';

        $table = $this->crawler->filter('table')->first();
        $table->filter('tr')->each(function (Crawler $row, $i) {
            if (false !== strpos($row->text(), 'Website')) {
                $this->data['website'] = str_replace('Website', '', $row->text());
            }
        });
    }

    private function getHeadquarters() : void
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
