<?php

declare(strict_types=1);

namespace App\Service\WebScraber\Publisher;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class PublisherScraber
{
    private string $url;

    private Crawler $crawler;

    private array $data = [];

    public function __construct(string $url)
    {
        $client = new Client([
            'base_uri' => $url,
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', '');
        $htmlContent = $response->getBody()->getContents();
        $this->crawler = new Crawler($htmlContent);
    }

    public function getGenaralInformation(){
        
        $elements = $this->crawler->filter('.mw-page-title-main');
        $elements->each(function (Crawler $node, $i) {
            $this->data['genaral-information']['name'] = $node->text();
        });

        $elements = $this->crawler->filter('.infobox-data');
        $elements->each(function (Crawler $node, $i) {
            if($i === 3){
                $pattern = '/\((\d{4}-\d{2}-\d{2})\)/';
                if (preg_match($pattern, $node->text(), $matches)) {
                    $date = $matches[1];
                    $this->data['genaral-information']['founded'] = $date;
                }
            }

            if($i === 4){
                $this->data['genaral-information']['funder'] = $node->text();
            }

            if($i === 5){
                $parts = explode(',', $node->text());
                $city = trim($parts[0]);

                $this->data['genaral-information']['headquarter'] = $city;

                $remaining = implode(',', array_slice($parts, 1));

                $this->data['genaral-information']['origin'] = $remaining;
            }

            if($i === 17){
                $this->data['genaral-information']['website'] = $node->text();
            }

        });

        return $this->data['genaral-information'];
    }

    public function getDescription(){

        var_dump($this->test());
        die();

        $elements = $this->crawler->filter('p');
        $elements->each(function (Crawler $node, $i) {
            $this->data['decription']['eng'][] = $node->text();
        });

        $string = implode(', ', $this->data['decription']['eng']);
        $this->data['decription']['eng'] = $string;

        return $this->data['decription'];

    }

    public function test(){
        $elements = $this->crawler->filter('li');
        $elements->each(function (Crawler $node, $i) {
            var_dump($node->text());
        });

        return $this->data;
    }
}
