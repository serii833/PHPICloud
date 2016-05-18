<?php

namespace PHPICloud;


class FindMyiPhoneService
{
    private $params;
    private $service_root;
    private $cookie_file;
    private $fmip_endpoint;
    private $fmip_refresh_url;
    private $fmip_sound_url;
    private $fmip_message_url;
    private $devices = [];

    function __construct($service_root, $params, $cookie_file) {
        $this->params = $params;
        $this->service_root = $service_root;
        $this->cookie_file = $cookie_file;

        $this->fmip_endpoint = "$this->service_root/fmipservice/client/web";
        $this->fmip_refresh_url = "$this->fmip_endpoint/refreshClient";
        $this->fmip_sound_url = "$this->fmip_endpoint/playSound";
        $this->fmip_message_url = "$this->fmip_endpoint/sendMessage";

        $this->refresh_client();
    }


    public function refresh_client()
    {
        $post_data = '{"clientContext": {"fmly": true, "shouldLocate": true, "selectedDevice": "all"}}';

        $resp = Helpers::make_post_request($this->fmip_refresh_url, $post_data, $this->params, $this->cookie_file);

        $data = json_decode($resp);


        $this->devices = [];
        foreach($data->content as $device) {
            $this->devices[] = $device;
        }
    }


    public function devices()
    {
        return $this->devices;
    }

    public function send_message($device_id, $text, $subject = 'PHPICloud: FindMyIPhone', $sound = false) {
        $data = json_encode([
            'device' => $device_id,
            'subject' => $subject,
            'sound' => $sound,
            'userText' => true,
            'text' => $text
        ]);

        $resp = Helpers::make_post_request($this->fmip_message_url, $data, $this->params, $this->cookie_file );
    }

    public function play_sound($device_id, $subject = 'PHPICloud: FindMyIPhone') {
        $data = json_encode([
            "device" => $device_id,
            "subject" => $subject
        ]);

        $resp = Helpers::make_post_request($this->fmip_sound_url, $data, $this->params, $this->cookie_file);
    }

}



class AppleDevice
{

}