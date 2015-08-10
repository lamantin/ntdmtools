<?php

@$domain = $_GET['link'];


$url = "http://data.alexa.com/data?cli=10&dat=snbamz&url=" . $domain;
//Initialize the Curl
$ch  = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
//Set the URL
curl_setopt($ch, CURLOPT_URL, $url);
//Execute the fetch
$data = curl_exec($ch);
//Close the connection
curl_close($ch);
$xml        = new SimpleXMLElement($data);
//Get popularity node
$popularity = $xml->xpath("//POPULARITY");
$reviews    = $xml->xpath('//REVIEWS');
$speed      = $xml->xpath('//SPEED');
$links      = $xml->xpath('//LINKSIN');
$category   = $xml->xpath('//CATS/CAT');
$name       = $xml->xpath('//DMOZ/SITE');

//Get the Rank attribute
// $rank = (string)$popularity[0]['TEXT'];
// echo $xml->saveXML();
$result = array(
    'name' => (string) $name[0]['TITLE'],
    'category' => (string) $category[0]['TITLE'],
    'rank' => number_format((int) $popularity[0]['TEXT'], 0),
    'links' => number_format((int) $links[0]['NUM'], 0),
    'reviews_stars' => (string) $reviews[0]['AVG'],
    'reviews_num' => (string) $reviews[0]['NUM'],
    'speed_time' => (int) $speed[0]['TEXT'] / 1000,
    'speed_percent' => (100 - (int) $speed[0]['PCT']) . '% of sites are faster.'
);

$var = intval(preg_replace('/[^\d.]/', '', $result['rank']));
header('Content-Type: application/json');
echo json_encode($var);
exit(0);
