<?php
require_once '../vendor/autoload.php';

use Ehann\RedisRaw\PredisAdapter;
use Ehann\RediSearch\Index;


header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, Accept, Authorization, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Header, X-CSRF-TOKEN, x-xsrf-token");

$json = file_get_contents('php://input');

$data = json_decode($json);

$redis = (new PredisAdapter())->connect('127.0.0.1', 6379);

$bookIndex = new Index($redis);

$bookIndex->addTextField('title')
    ->addTextField('title')
    ->addTextField('description')
    ->addTextField('link')
    ->addTextField('pubDate')
    ->setIndexName('index')
    ->create();

$results = [];
$query = '';


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if(!isset($data->keywords)){
        echo json_encode([]);
        die();
    }
    if(count($data->keywords) === 0){
        echo json_encode([]);
        die();
    }

    foreach ($data->keywords as $keyword){
        $query .= $keyword . ' ';
    }

    try{
        $result = $bookIndex->search($query);
        $results = $result->getDocuments();
    }catch(Exception $e){
        error_log($e);
    }
}

$response = [];
foreach ($results as $res){
    if(isBetween($data->from, $data->to, $res->pubDate)){
        array_push($response, $res);
    }
}

echo json_encode($response);
die();


function isBetween($from, $to, $date){
    $from = strtotime($from);
    $to = strtotime($to);
    $date = strtotime($date);

    if(!isset($from) or !isset($to) or $from == '' or $to == ''){
        return true;
    }

    if($date > $from && $date < $to){
        return true;
    }

    return false;
}