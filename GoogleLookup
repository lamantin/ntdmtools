<?php

/** 
 * Simple Google Domain LookUp 
 * @source  http://agichevski.com/2014/01/04/integrate-google-safe-browsing-lookup-api/
 * @author stvan <istvan.makai@gmail.com>
 */
define('API_KEY', "");
define('PROTOCOL_VER', '3.0');
define('CLIENT', 'api');
define('APP_VER', '1.0');

class GoogleLookup {

    public function __construct($url = false) {

        if (filter_var(gethostbyname($url), FILTER_VALIDATE_IP)) {
            return $this->getinfo($url);
        }
    }

    public function get_data($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array(
            'status' => $httpStatus,
            'data' => $data
        );
    }

    public function send_response($input) {
        if (!empty($input)) {
            $urlToCheck = urlencode($input);

            $url = 'https://sb-ssl.google.com/safebrowsing/api/lookup?client=' . CLIENT . '&apikey=' . API_KEY . '&appver=' . APP_VER . '&pver=' . PROTOCOL_VER . '&url=' . $urlToCheck;

            $response = $this->get_data($url);

            if ($response ['status'] == 204) {
                return json_encode(array(
                    'status' => 204,
                    'checkedUrl' => $urlToCheck,
                    'message' => 'The website is not blacklisted and looks safe to use.'
                ));
            } elseif ($response ['status'] == 200) {
                return json_encode(array(
                    'status' => 200,
                    'checkedUrl' => $urlToCheck,
                    'message' => 'The website is blacklisted as ' . $response ['data'] . '.'
                ));
            } else {
                return json_encode(array(
                    'status' => 501,
                    'checkedUrl' => $urlToCheck,
                    'message' => 'Something went wrong on the server. Please try again.'
                ));
            }
        } else {
            return json_encode(array(
                'status' => 401,
                'checkedUrl' => '',
                'message' => 'Please enter URL.'
            ));
        }
        ;
    }

    public function getinfo($url) {

        $checkMalware = $this->send_response($url);
        $checkMalware = json_decode($checkMalware, true);
        $malwareStatus = $checkMalware['status'];

        return $malwareStatus;
    }

}
