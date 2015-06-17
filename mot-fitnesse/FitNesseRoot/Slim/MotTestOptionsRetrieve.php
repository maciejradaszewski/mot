<?php

use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\Tester1CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class MotTestOptionsRetrieve
{
    private $motTestNumberGenerated;
    private $result;

    private $siteId;
    private $testerUsername;

    public function __construct($testerUsername, $siteId)
    {
        $this->testerUsername = $testerUsername;
        $this->siteId = $siteId;
    }

    public function beginTable()
    {
        $credentials = new \MotFitnesse\Util\CredentialsProvider(
            $this->testerUsername,
            TestShared::PASSWORD
        );

        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::createForCreds($credentials));
        $vehicleId = $vehicleTestHelper->generateVehicle();

        $motTestHelper = new MotTestHelper($credentials);
        $this->motTestNumberGenerated = $motTestHelper
            ->createPassedTest(
                (new MotTestCreate())
                    ->vehicleId($vehicleId)
                    ->siteId($this->siteId)
            );
    }

    public function execute()
    {
        $urlBuilder = UrlBuilder::motTestOptions($this->motTestNumberGenerated);

        $this->result = TestShared::execCurlForJsonFromUrlBuilder(
            new Tester1CredentialsProvider(),
            $urlBuilder
        )['data'];
    }

    public function startedDate()
    {
        return $this->result['startedDate'];
    }

    public function vehicleMake()
    {
        return $this->result['vehicle']['make'];
    }

    public function vehicleModel()
    {
        return $this->result['vehicle']['model'];
    }

    public function vehicleRegistrationNumber()
    {
        return $this->result['vehicle']['vehicleRegistrationNumber'];
    }
}
