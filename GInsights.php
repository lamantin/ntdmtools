<?php

class GInsights{

public $apikey = '__YOUR__API___KEY';

public $apiUrl = 'https://www.googleapis.com/pagespeedonline/v2/runPagespeed';

public function __construct($url=false,$strategy=false){

	$this->url = $url;
	if($strategy) $this->strategy = $strategy;
	
}

public function prepareUrl($url=false){
	return  $this->apiUrl.'?url=http://'.$this->url.'&key='.$this->apikey.'&locale=hu';
}

public function checkPageSpeed($url,$strategy=false){

  if($strategy) $url.='&strategy='.$strategy;


  if ($result == '') {  
    $ch = curl_init();  
    $timeout = 60;  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
    $result = curl_exec($ch);  
    curl_close($ch);  
  }  
  return $result;  
}

public function prepareResonse($results){
	$res = (json_decode($results,true));
		return $res['ruleGroups'];
}

public function collapse(){
	$response = [];
	if(isset($this->strategy)) {
		return $this->prepareResonse($this->checkPageSpeed($this->prepareUrl(),$this->strategy));
	} 
	$strategies = ['mobile','desktop'];
	foreach($strategies as $strategy){
		$response[$strategy] = $this->prepareResonse($this->checkPageSpeed($this->prepareUrl(),$strategy));
	}

	return $response;
}
}
