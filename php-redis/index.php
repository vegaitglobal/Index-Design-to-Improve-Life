<?php
require_once 'vendor/autoload.php';

use GO\Scheduler;
use Ehann\RedisRaw\PredisAdapter;
use Ehann\RediSearch\Index;
use Predis\Client as PredisClient;
require 'crawler.php';

$redis = (new PredisAdapter())->connect('127.0.0.1', 6379);

try {
    $predis = new PredisClient();
}
catch (Exception $e) {
    die($e->getMessage());
}

$bookIndex = new Index($redis);

$bookIndex->addTextField('title')
    ->addTextField('title')
    ->addTextField('description')
    ->addTextField('link')
    ->addTextField('pubDate')
//    ->addTextField('build_date')
    ->setIndexName('index')
    ->create();


$urls = [
    'https://www.fastcompany.com/latest/rss',
    'https://bigthink.com/feeds/main.rss',
//    'https://biomimicry.org/feed/',
    'https://www.theguardian.com/international/rss',
    'https://www.ft.com/?format=rss',
    'https://www.designboom.com/feed/',
    'https://www.vice.com/en_ca/rss',
    'https://pa.tedcdn.com/feeds/talks.rss',
    'http://www.designindaba.com/rss',
    'https://www.vice.com/en_au/rss',
    'https://www.yatzer.com/rss.xml',
    'https://singularityhub.com/feed/',
    'https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml',
    'https://www.vice.com/en_us/rss',
    'https://www.forbes.com/innovation/feed2/',
    'http://www.sparknews.com/en/feed/',
    'https://www.vice.com/en_uk/rss',
    'https://www.wired.com/feed/rss',
    'http://danishdesignaward.com/en/feed/',
    'https://motherboard.vice.com/en_us/rss',
    'http://designandflow.co/?format=feed&type=rss',
    'http://feeds.feedburner.com/design-milk',
    'http://feeds.feedburner.com/Wallpaperfeed',
    'http://feeds.nature.com/nature/rss/current',
    'https://www.tue.nl/universiteit/nieuws-en-pers/nieuws/news.xml',
    'https://www.technologyreview.com/stories.rss',
    'http://feeds.bbci.co.uk/news/technology/rss.xml',
    'https://ssir.org/site/rss_2.0',
    'https://medium.com/feed/@Medium',
    'https://medium.com/feed/the-story',
    'https://medium.com/feed/invironment/tagged/food'

];
$build_date = $predis->get('build_date');

$predis->set('build_date', time());
foreach ($urls as $url){
    echo "Crawling ...";
    $crawler = new Crawler($url);
    $posts = $crawler->json();

    foreach ($posts['items'] as $post){
        if(strtotime($post['pubDate']) > $build_date){
            $bookIndex->add([
                'title' => $post['title'],
                'description' => $post['description'],
                'link' => $post['link'],
                'pubDate' => $post['pubDate']
            ]);
        }
    }
}
