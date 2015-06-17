<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm2375FuzzySearch {

    private $username = 'tester1';
    private $password = TestShared::PASSWORD;

    private $registration;

    private $result;
    private $count;
    private $foundRegistration;

    public function setRegistration($registration)
    {
        $this->registration = $registration;

        $this->execute();
    }

    public function result()
    {
        return $this->result;
    }

    public function count()
    {
        if ($this->count == null) {
            return 'INAPPLICABLE';
        }

        return $this->count;
    }

    public function foundRegistration()
    {
        if ($this->foundRegistration == null) {
            return 'INAPPLICABLE';
        }

        return $this->foundRegistration;
    }

    protected function execute()
    {
        $curlHandle = curl_init(
            (new UrlBuilder())->vehicle()->queryParams(
                array(
                    TestShared::REG_QUERY_PARAM => $this->registration,
                    TestShared::VIN_TYPE_PARAM  => 'noVin',
                )
            )->toString()
        );
        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser($this->username, $this->password, $curlHandle);

        $jsonResult = TestShared::execCurlForJson($curlHandle);
        if (array_key_exists('errors', $jsonResult)) {
            $this->result = $jsonResult['errors'][0];
            return;
        }

        $data = $jsonResult['data'];
        $this->result = $data['resultType'];
        
        if (isset($data['resultCount'])) {
            $this->count = $data['resultCount'];
        } else {
            $this->count = null;
        }

        if (isset($data['vehicle'])) {
            $this->foundRegistration = $data['vehicle']['registration'];
        } else {
            $this->foundRegistration = null;
        }
    }

} 