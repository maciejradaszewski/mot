<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Testing\ReplacementCertificateHelper;
use MotFitnesse\Testing\Vehicle\VehicleHelper;
use DvsaCommon\Enum\ColourCode;

class Vm2384ModifyVehicleRecordDuplicateReplacement
{
    protected $vehicleTestingStationId = '1';
    private $testerUsername;
    private $siteId;
    private $username = 'areaoffice1user';
    private $password = TestShared::PASSWORD;
    private $vehicleId;
    private $motTestNumber;
    private $primaryColourId;
    private $primaryColour;
    private $secondaryColourId;
    private $secondaryColour;
    private $vin;
    private $registration;
    private $modelId;
    private $model;
    private $makeId;
    private $make;
    private $countryOfRegistration;
    private $oneTimePassword;

    /** @var  \MotFitnesse\Testing\ReplacementCertificateHelper */
    private $replacementCertificateHelper;

    public function __construct()
    {
        $this->replacementCertificateHelper = new ReplacementCertificateHelper($this->username, $this->password);
    }

    public function setTesterUsername($v)
    {
        $this->testerUsername = $v;
    }

    public function setSiteId($v)
    {
        $this->siteId = $v;
    }

    public function isVehicleDataUpdated()
    {
        $this->vehicleId = $this->generateVehicle();

        $motTestHelper = new MotTestHelper(new CredentialsProvider($this->testerUsername, $this->password));
        $this->motTestNumber = $motTestHelper->createPassedTest($this->createMotTestCreateObject());

        $result = $this->replacementCertificateHelper->create($this->motTestNumber);
        $draftId = (int)$result['data']['id'];

        $this->replacementCertificateHelper->update(
            $draftId,
            [
                'primaryColour'        => $this->primaryColourId,
                'secondaryColour'      => $this->secondaryColourId,
                'vin'                  => $this->vin,
                'vrm'                  => $this->registration,
                'model'                => $this->modelId,
                'make'                 => $this->makeId,
                'reasonForReplacement' => 'XYZ'
            ]
        );
        $response = $this->replacementCertificateHelper->apply($draftId, $this->oneTimePassword);

        return (new VehicleHelper($this->vehicleId))->savedCorrectly(
            [
                'primaryColour'   => $this->primaryColour,
                'secondaryColour' => $this->secondaryColour,
                'vin'             => $this->vin,
                'registration'    => $this->registration,
                'model'           => $this->model,
                'make'            => $this->make,
            ],
            $response,
            function ($vehicle) {
                return [
                    'primaryColour'   => $vehicle['colour']['name'],
                    'secondaryColour' => $vehicle['colourSecondary']['name'],
                    'vin'             => $vehicle['vin'],
                    'registration'    => $vehicle['registration'],
                    'model'           => $vehicle['modelName'],
                    'make'            => $vehicle['makeName'],
                ];
            }
        );
    }

    /**
     * @return int
     */
    private function generateVehicle()
    {
        $vehicleHelper = new VehicleTestHelper(FitMotApiClient::create($this->testerUsername, TestShared::PASSWORD));
        return $vehicleHelper->generateVehicle(
            [
                'testClass'             => VehicleClassCode::CLASS_4,
                'colour'                => ColourCode::NOT_STATED,
                'secondaryColour'       => ColourCode::PURPLE,
                'vin'                   => $this->vin,
                'registrationNumber'    => $this->registration,
                'make'                  => $this->makeId,
                'model'                 => $this->modelId,
                'countryOfRegistration' => $this->countryOfRegistration,
            ]
        );
    }

    /**
     * @return \MotFitnesse\Testing\Objects\MotTestCreate
     */
    private function createMotTestCreateObject()
    {
        $motTestObject = new \MotFitnesse\Testing\Objects\MotTestCreate();
        $motTestObject->vehicleId($this->vehicleId)->siteId($this->siteId);

        return $motTestObject;
    }

    public function setPrimaryColourId($value)
    {
        $this->primaryColourId = $value;
    }

    public function setPrimaryColour($value)
    {
        $this->primaryColour = $value;
    }

    public function setSecondaryColourId($value)
    {
        $this->secondaryColourId = $value;
    }

    public function setSecondaryColour($value)
    {
        $this->secondaryColour = $value;
    }

    public function setVin($value)
    {
        $this->vin = $value;
    }

    public function setRegistration($value)
    {
        $this->registration = $value;
    }

    public function setModelId($value)
    {
        $this->modelId = $value;
    }

    public function setModel($value)
    {
        $this->model = $value;
    }

    public function setMakeId($value)
    {
        $this->makeId = $value;
    }

    public function setMake($value)
    {
        $this->make = $value;
    }

    public function setCountryOfRegistration($value)
    {
        $this->countryOfRegistration = $value;
    }

    public function setOneTimePassword($value)
    {
        $this->oneTimePassword = $value;
    }

    public function setInfoAboutResult($value)
    {
    }
}
