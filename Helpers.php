<?php

namespace PHPICloud;


use IPhoneLocator\Log;

class Helpers
{

    public static function make_get_request($url, $params, $cookie_file) {
        return Helpers::make_request("GET", $url, null, $params, $cookie_file);
    }

    public static function make_post_request($url, $post_data, array $params, $cookie_file) {
        return Helpers::make_request("POST", $url, $post_data, $params, $cookie_file);
    }

    private static function make_request($type, $url, $post_data, array $params, $cookie_file) {
        $headers = [];
        $headers[] = "Origin: https://www.icloud.com";


        $url = $url . "?" . http_build_query($params);


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_TIMEOUT => 9,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_VERBOSE => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => true,
            CURLOPT_URL => $url,

        ));

        if ($cookie_file != null){
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
        }


        if ($type == "POST") {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        }

//        debug proxy
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 1);
//        curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:8888');
//        curl_setopt($curl, CURLOPT_VERBOSE, true);


        $result = curl_exec($curl);


        $curl_header_size = curl_getinfo($curl,CURLINFO_HEADER_SIZE);
        $body = mb_substr($result, $curl_header_size);
//        $headers = mb_substr($result, 0, $curl_header_size);


        curl_close($curl);


        return $body;
    }

}