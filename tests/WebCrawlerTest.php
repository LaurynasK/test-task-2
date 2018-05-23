<?php


namespace App\Tests;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;


class WebCrawlerTest extends TestCase
{
    public $goutteClient;

    public function __construct()
    {
        parent::__construct();
        $this->goutteClient = new Client();
    }

    public function testShow()
    {
        $response = $this->goutteClient->request('GET', 'http://symphart.test/show');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        foreach($data as $item){
            $this->assertArrayHasKey("title", $item);
            $this->assertArrayHasKey("location", $item);
            $this->assertArrayHasKey("apply_link", $item);
        }
    }

    public function testSave()
    {
        $response = $this->goutteClient->request('GET', 'http://symphart.test/');
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('saved', $data);
    }

    public function testShowRaw()
    {
        $response = $this->goutteClient->request('GET', 'http://symphart.test/show-raw');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        foreach($data as $item){
            $this->assertArrayHasKey("title", $item);
            $this->assertArrayHasKey("location", $item);
            $this->assertArrayHasKey("apply_link", $item);
        }
    }
}