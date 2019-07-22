<?php
namespace SHUFLER\ShuflerBundle\Service;

class Curl {
    
    public function getInit($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        return $curl;
    }
}