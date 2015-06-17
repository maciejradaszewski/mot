<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm14Login
{
    public $username;
    public $password;

    public function setUserName($value)
    {
        $this->username = $value;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function result()
    {

        //This is the url of the API
        //Will need to import this dynamically during build as the target may change
        $ch = curl_init((new UrlBuilder())->session()->toString());

        TestShared::SetupCurlOptions($ch);
        $postFields = [
            'username' => $this->username,
            'password' => $this->password
        ];
        TestShared::SetCurlPost($ch, $postFields);

        $jsonResult = TestShared::execCurlForJson($ch);

        //The json object is in two layers so we get the code value from the data object and return it
        return $jsonResult['data']['code'];
    }

    public function setInfoAboutAccount()
    {
    }
}
