<?php
require_once 'vendor/autoload.php';

use Ehann\RedisRaw\PredisAdapter;
use Ehann\RediSearch\Index;
require 'crawler.php';

$redis = (new PredisAdapter())->connect('127.0.0.1', 6379);

$bookIndex = new Index($redis);

$bookIndex->addTextField('title')
    ->addTextField('title')
    ->addTextField('description')
    ->addTextField('link')
//    ->addTextField('build_date')
    ->setIndexName('zeka')
    ->create();


//$crawler = new Crawler('http://feeds.feedburner.com/Wallpaperfeed');
//$posts = $crawler->json();
//
////$build_date = $posts[0]['lastBuildDate'];
////var_dump($posts);
//foreach ($posts['items'] as $post){
//    $bookIndex->add([
//        'title' => $post['title'],
//        'description' => $post['description'],
//        'link' => $post['link'],
////        'build_date' => $build_date
//    ]);
//}


$result = $bookIndex->search('The11 New York hotels we’re checking into this year');

$result->getCount();     // Number of documents.
$result->getDocuments(); // Array of matches.

// Documents are returned as objects by default.
var_dump($result);

$result = $bookIndex->search('hakaton');

echo $result->getCount();     // Number of documents.
var_dump($result->getDocuments()); // Array of matches.


var_dump($result->getCount());

