<?php

require_once 'configure_autoload.php';

use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm61TestRfrsAreReturned
{
    private $credentials;
    private $testItemSelectorId;
    private $result;
    private $parentItemSelectors;
    private $hasError = false;
    private $motTestNumber;
    private $siteId;

    const ERROR_TEXT = 'Error';

    public function __construct($testerUsername, $siteId)
    {
        $this->credentials = new CredentialsProvider($testerUsername, TestShared::PASSWORD);
        $this->siteId = $siteId;
    }

    public function beginTable()
    {
        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::createForCreds($this->credentials));
        $vehicleId = $vehicleTestHelper->generateVehicle();

        $motTestHelper = new MotTestHelper($this->credentials);
        $this->motTestNumber = $motTestHelper
            ->createPassedTest(
                (new MotTestCreate())
                    ->vehicleId($vehicleId)
                    ->siteId($this->siteId)
            );
    }

    public function name()
    {
        $curlHandle = curl_init(
            (new UrlBuilder())
                ->motTest()
                ->routeParam('motTestNumber', $this->motTestNumber)
                ->testItemSelector()
                ->routeParam('tisId', $this->testItemSelectorId)
                ->toString()
        );

        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser(
            $this->credentials->username,
            $this->credentials->password,
            $curlHandle
        );
        try {
            $this->result = TestShared::executeAndReturnResponseAsArray($curlHandle);
            $this->parentItemSelectors = $this->result['parentTestItemSelectors'];
        } catch (ApiErrorException $ex) {
            $this->hasError = true;
            return $ex->getMessage();
        }

        return $this->result['testItemSelector']['name'];
    }

    public function oneCategoryUp()
    {
        if ($this->hasError) {
            return self::ERROR_TEXT;
        }
        if (count($this->parentItemSelectors) >= 1) {
            return $this->parentItemSelectors[0]['name'];
        } else {
            return '';
        }
    }

    public function twoCategoriesUp()
    {
        if ($this->hasError) {
            return self::ERROR_TEXT;
        }
        if (count($this->parentItemSelectors) >= 2) {
            return $this->parentItemSelectors[1]['name'];
        } else {
            return '';
        }
    }

    public function setCategoryId($value)
    {
        $this->testItemSelectorId = $value;
    }

    public function setInfoAboutTest($value)
    {
    }
}
