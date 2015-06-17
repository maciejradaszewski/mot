<?php
require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Enforcement_InspectionLocation
{
    protected $siteId;
    protected $location;
    protected $response;

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        $this->response = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            new \MotFitnesse\Util\FtEnfTesterCredentialsProvider(),
            (new UrlBuilder())->inspectionLocation(),
            [
                'siteid'   => $this->siteId,
                'location' => $this->location
            ],
            [
                'X-Dvsa-Validation' => 1
            ]
        );
    }

    public function callFailed()
    {
        return isset($this->response['errors']);
    }

    public function callOk()
    {
        return !isset($this->response['errors']);
    }

    public function firstError()
    {
        if ($this->callFailed() && count($this->response['errors']) > 0) {
            return $this->response['errors'][0]['message'];
        } else {
            return 'none';
        }
    }

    public function returnedSiteName()
    {
        if ($this->callOk()) {
            return $this->response['data']['sitename'];
        }
        return null;
    }

    public function returnedSiteId()
    {
        if ($this->callOk()) {
            return $this->response['data']['siteid'];
        }
        return null;
    }

    public function returnedLocation()
    {
        if ($this->callOk()) {
            if (!empty($this->response['data']['location'])) {
                return $this->response['data']['location'];
            }
        }
        return null;
    }
}
