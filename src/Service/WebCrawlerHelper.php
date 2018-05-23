<?php

namespace App\Service;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebCrawlerHelper
{
    /**
     * Wrapped Goutte Client object
     *
     * @var GoutteClient
     */
    public $goutteClient;

    /**
     * Wrapped Symfony crawler object
     *
     * @var crawler
     */
    public $crawler;

    /**
     * WebCrawlerHelper constructor.
     *
     * @param string $url
     * @param string $clientMethod
     */
    public function __construct(string $url, string $clientMethod){
        $this->goutteClient = new Client();
        $html = $this->goutteClient->request($clientMethod, $url)->html();

        $this->crawler = new Crawler($html);
    }

    /**
     * Helper main function crawling through webpage by user provided parameters
     *
     * @param string $searchIn
     * @param array $searchFor
     * @param bool $cleanUp
     *
     * @return array
     */
    public function crawl(string $searchIn, array $searchFor, bool $cleanUp = true){
        $crawler = $this->crawler->filter($searchIn);

        return $crawler->each(
            function (Crawler $node) use ($searchFor, $cleanUp) {
                $final = array();

                foreach ($searchFor as $key => $item){
                    if(is_string($item)){
                        $final[$key] = $node->filter($item)->text();
                    } else {
                        $final[$key] = $this->extractString($node->filter($item[0])->extract($item[1]));
                    }
                }
                return $cleanUp ? $this->cleanUp($final) : $final;
            }
        );
    }

    /**
     * Extract string from Array.
     *
     * @param array $arr
     *
     * @return string
     */
    public function extractString(Array $arr){
        $final = '';
        foreach ($arr as $item){
            if(is_array($item)){
                $final .= $this->extractString($item);
            } else {
                $final .= $item;
            }
        }
        return $final;
    }

    /**
     * cleanUp from /n, /t and etc.
     *
     * @param array $arr
     *
     * @return array
     */
    public function cleanUp(Array $arr){
        $final = array();
        $replace = '/\s/';

        foreach ($arr as $key => $item){
            if(is_array($item)){
                $final[$key] = $this->cleanUp($item);
            } elseif (is_string($item)){
                if (!filter_var($item, FILTER_VALIDATE_URL)) {
                    $final[$key] = preg_replace($replace, " ", $item);
                } else {
                    $final[$key] = $item;
                }
            }
        }
        return $final;
    }
}