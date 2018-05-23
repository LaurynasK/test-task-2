<?php
namespace App\Controller;


use App\Service\SchemaJsonHelper;
use JsonSchema\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use App\Service\FileHelper;
use App\Service\WebCrawlerHelper;

class WebCrawler extends Controller
{
    /**
     * where to search
     *
     * @var searchIn
     */
    protected $searchIn;

    /**
     * what to search
     *
     * It must be array with keys, key is how it will be called in json
     * and values must be for example 'div.widget.panel' that means
     * that we are searching for <div class="media panel"> (considering
     * that "media panel" is two classes)
     *
     * @var seachFor
     */
    protected  $searchFor;


    public function __construct()
    {
        $this->searchIn  = (isset($this->searchIn)) ? $this->searchIn : 'div.widget.panel';
        $this->searchFor = (isset($this->searchFor)) ? $this->searchFor : array(
            'title' => 'h4.media-heading',
            'location' => 'p.location',
            'date' => 'p.date',
            'content' => 'article',
            'apply_link' => ['p.text-center > a', 'href']
        );
    }

    /**
     * @Route("/", name="save")
     * @Method({"GET"})
     */
    public function saveToFile(WebCrawlerHelper $crawlerHelper, FileHelper $fileHelper, SchemaJsonHelper $schemaJsonHelper){
        // extract info from web
        $crawled = $crawlerHelper->crawl($this->searchIn, $this->searchFor);
        // open schema.json
        $schemaJson = $fileHelper->open('schema');
        // get validated array of crawled data
        $final = $schemaJsonHelper->checkValidation($crawled, $schemaJson);

        // save to file,
        // last parameter is false so file jobs.json will be overwritten every time
        if($fileHelper->save($final, false)){
            $response = "saved, " . count($final) . " was crawled ";
        } else {
            $response = "failed";
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/show", name="show")
     * @Method({"GET"})
     */
    public function show(FileHelper $fileHelper){
        $final = $fileHelper->open();
        //dump(json_decode($final, true));exit;
        // workaround!, because i did not find the way how to return acceptable json string
        // otherwise i always get json_encoded string with something like u2022, u00a0 and etc. inside...
        $response = new Response($final);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/show-raw", name="show-raw")
     * @Method({"GET"})
     */
    public function showRaw(WebCrawlerHelper $crawlerHelper){
        $final = $crawlerHelper->crawl($this->searchIn, $this->searchFor);
        // workaround!, because i did not find the way how to return acceptable json string
        // otherwise i always get json_encoded string with something like u2022, u00a0 and etc. inside...
        $response = new Response(json_encode($final, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}