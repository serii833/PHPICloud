<?php

namespace PHPICloud;

require_once "FindMyiPhoneService.php";
require_once "AccountService.php";
require_once "Helpers.php";

class PHPICloud
{
    private $username;
    private $password;
    private $client_id;

    private $cookie_file;

    /*
     reminders, calendar, docws, ubiquity, streams, keyvalue, ckdatabasews, archivews, push, drivews, iwmb
     cksharews, iworkexportws, findme, iworkthumbnailws, fmf, contacts, account
    */
    public $webServices = [];

    private $params = [];
    private $findMyIPhoneService = null;
    private $accountService = null;


    public function __construct($username, $password)
    {
        if (!extension_loaded('curl')) {
            throw new Exception('PHP extension cURL is not loaded.');
        }

        $this->username = $username;
        $this->password = $password;

        $this->client_id = uniqid();

        $this->params['clientBuildNumber'] = '14E45';
        $this->params['clientid'] = $this->client_id;

        $this->cookie_file = $this->get_cookie_file();

        $this->authenticate();
    }


    public function authenticate()
    {
        $post_data = json_encode(["apple_id" => $this->username, "password" => $this->password, "extended_login" => false]);
        $login_url = "https://setup.icloud.com/setup/ws/1/login";

        //delete cookie file to avoid sending old cookies
        if (file_exists($this->cookie_file))
            unlink($this->cookie_file);

        $resp = Helpers::make_post_request($login_url, $post_data, $this->params, $this->cookie_file);

        $data = json_decode($resp);

        $this->webServices = $data->webservices;
        $this->params['dsid'] = $data->dsInfo->dsid;

        return $resp;
    }


    public function getFindMyiPhoneService()
    {
        $service_root = $this->webServices->findme->url;
        if ($this->findMyIPhoneService == null)
            $this->findMyIPhoneService = new FindMyiPhoneService($service_root, $this->params, $this->cookie_file);

        return $this->findMyIPhoneService;
    }

    public function getAccountService()
    {
        $service_root = $this->webServices->account->url;
        if($this->accountService == null)
            $this->accountService = new AccountService($service_root, $this->params, $this->cookie_file);

        return $this->accountService;
    }



    private function get_cookie_file()
    {
        return sys_get_temp_dir() . '/' . "PHPICloud-" . $this->username . ".cookies";
    }
}