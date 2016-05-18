<?php

namespace PHPICloud;


class AccountService
{
    private $service_root;
    private $params;
    private $account_device_endpoint;
    private $devices_url;
    private $cookie_file;

    public function __construct($service_root, $params, $cookie_file)
    {
        $this->service_root = $service_root;
        $this->params = $params;
        $this->cookie_file = $cookie_file;

        $this->account_device_endpoint = $service_root . "/setup/web/device";
        $this->devices_url = $this->account_device_endpoint . "/getDevices";
    }

    public function devices()
    {
        $resp = Helpers::make_get_request($this->devices_url, $this->params, $this->cookie_file);

        $data = json_decode($resp);

        $devices = [];

        foreach ($data->devices as $device_info)
            $devices[] = new AccountDevice($device_info);

        return $devices;
    }
}


class AccountDevice
{
    public $serialNumber;
    public $osVersion;
    public $modelLargePhotoURL2x;
    public $modelLargePhotoURL1x;
    public $name;
    public $imei;
    public $model;
    public $udid;
    public $modelSmallPhotoURL2x;
    public $modelSmallPhotoURL1x;
    public $modelDisplayName;


    public function __construct($device_info)
    {
        $this->serialNumber = $device_info->serialNumber;
        $this->osVersion = $device_info->osVersion;
        $this->modelLargePhotoURL2x = $device_info->modelLargePhotoURL2x;
        $this->modelLargePhotoURL1x = $device_info->modelLargePhotoURL1x;
        $this->name = $device_info->name;
        $this->imei = $device_info->imei;
        $this->model = $device_info->model;
        $this->udid = $device_info->udid;
        $this->modelSmallPhotoURL2x = $device_info->modelSmallPhotoURL2x;
        $this->modelSmallPhotoURL1x = $device_info->modelSmallPhotoURL1x;
        $this->modelDisplayName = $device_info->modelDisplayName;
    }
}