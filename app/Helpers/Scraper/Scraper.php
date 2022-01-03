<?php
namespace App\Helpers\Scraper;
require_once("fabpot_goutte/vendor/autoload.php");

use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

class Scraper extends Crawler{

    public function client(){
        return new Client();
    }

    public function crawler(){
        return $this;
    }
}
?>