<?php

require_once 'configure_autoload.php';
use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm302MotTestRfrSearch
{
    private $credentials;
    private $motTestNumber;
    private $searchString;
    private $siteId;

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

    public function setSearchString($value)
    {
        $this->searchString = $value;
    }

    public function foundResults()
    {
        $curlHandle = curl_init(
            (new UrlBuilder())
                ->motTest()
                ->routeParam('motTestNumber', $this->motTestNumber)
                ->reasonForRejection()
                ->queryParam('search', $this->searchString)
                ->toString()
        );

        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser(
            $this->credentials->username,
            $this->credentials->password,
            $curlHandle
        );

        $jsonResult = TestShared::execCurlForJson($curlHandle);

        $foundResults = "false";
        if (array_key_exists('data', $jsonResult)
            && array_key_exists('reasonsForRejection', $jsonResult['data'])
            && count($jsonResult['data']['reasonsForRejection']) > 0) {
            $foundResults = "true";
        }

        return $foundResults;
    }

    public function setInfoAboutSearchString($value)
    {
    }
}
