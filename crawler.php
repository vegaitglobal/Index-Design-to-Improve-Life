<?php

$url= 'https://www.ft.com/?format=rss';

class Crawler
{

    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    function validateUrl($url)
    {
        if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;

    }


    function parse($xml)
    {
        $parser = [];
        if (is_object($xml)) {
            // channel info
            $parser['channel'] = [
                'title' => (string)$xml->channel->title,
                'link' => (string)$xml->channel->link,
                'img' => (string)$xml->channel->image->url,
                'description' => (string)$xml->channel->description,
                'lastBuildDate' => (string)$xml->channel->lastBuildDate,
                'generator' => (string)$xml->channel->generator
            ];

            // feed items
            $parser['items'] = [];

            foreach ($xml->channel->item as $item) {
                array_push($parser['items'], [
                    'title' => (string)$item->title,
                    'link' => (string)$item->link,
                    'description' => (string)$item->description,
                    'description_text' => strip_tags($item->description),
                    'pubDate' => (string)$item->pubDate
                ]);
            }

            return $parser;
        } else json_response(['error' => 'Unable to Parse xml format.'], 500);


    }

    function fetch()
    {

        $content = @file_get_contents($this->url);

        if (!$content) return false;
        $content = @simplexml_load_string($content);
        return $content;
    }


    function json()
    {
        if (!$xmlData = $this->fetch($this->url)) {
            $last_error = error_get_last();

            json_response([
                "error" => "Unable to connect to URL"],
                500);
        }

        return $this->parse($xmlData);
    }

    function writeToDB($data)
    {

        $arrayLen = count($data["items"]);
        for ($i = 0; $i < $arrayLen; $i++) {
            echo "Title: ";
            echo $data["items"][$i]["title"];
            echo "<br>";
            echo "Desc: ";
            echo $data["items"][$i]["description"];
            echo "<br>";
            echo "Link: ";
            echo $data["items"][$i]["link"];
            echo "<br>";
            echo "PubDate: ";
            echo $data["items"][$i]["pubDate"];
            echo "<br>";
            echo "*************************************************************************************";
            echo "<br>";
        }

        //echo "Title: ";
        ///echo $data["items"][0]["title"];
        //echo "<br>";
        //echo $data["items"][0];
        //echo $data["items"][0]["title"];
        //echo $data["items"][0]["title"];

        //$data = is_string($data) ? [$data] : $data;
        //http_response_code(200);
        //header('Content-Type: application/json');
        //die(json_encode($data));
    }

}
?>
