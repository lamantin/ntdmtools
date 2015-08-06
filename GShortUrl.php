/**
 * GShortUrl google short url creator simple Class
 * Usage :: //
 *   App::import('Vendor', 'GShortUrl', array('file' => 'gshorturl/GShortUrl.php'));
 *         $ShortUrl = new GShortUrl();
 *   echo ($ShortUrl->send("http://yourUrl.com")['id']);
 * @author stvan
 */
class GShortUrl {
    public $api_url="https://www.googleapis.com/urlshortener/v1/url";
    public $key ="";
    protected $apiURL;


    public function __construct($url=false) {
        $this->apiURL = $this->api_url.'?key='.$this->key;
    }


    public function shorten($url)
    {
        $response = $this->send($url);

	return isset($response['id']) ? ($response['id']) : false;
    }

    public function expand($url)
    {
	$response = $this->send($url,false);
	return isset($response['longUrl']) ? $response['longUrl'] : false;
    }

    public  function send($url,$shorten = true) {
	$ch = curl_init();
	if($shorten) {
	    curl_setopt($ch,CURLOPT_URL,$this->apiURL);
	    curl_setopt($ch,CURLOPT_POST,1);
	    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
	    curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
	}
	else {
	    curl_setopt($ch,CURLOPT_URL,$this->apiURL.'&shortUrl='.$url);
	}
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        $result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result,true);
    }
}

