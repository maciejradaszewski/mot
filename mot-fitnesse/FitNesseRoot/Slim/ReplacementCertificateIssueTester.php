<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;

class ReplacementCertificateIssueTester
{
    private $username;
    private $password = TestShared::PASSWORD;
    private $vehicleId;
    private $siteId;
    private $oldPrimaryColour;
    private $oldSecondaryColour;
    private $oneTimePassword;
    private $newPrimaryColour;
    private $newSecondaryColour;
    private $newOdometerReading;
    private $oldOdometerReading;
    /** @var MotTestHelper */
    private $motTestHelper;
    /** @var  \MotFitnesse\Testing\ReplacementCertificateHelper */
    private $replacementCertificateHelper;

    /*
     * @param string $testerUsername
     */
    public function __construct($testerUsername)
    {
        $this->username = $testerUsername;
        $this->motTestHelper = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider(
            $this->username,
            $this->password
        ));
        $this->replacementCertificateHelper
            = new \MotFitnesse\Testing\ReplacementCertificateHelper($this->username, $this->password);
    }

    public function execute()
    {
        $this->vehicleId = $this->vehicleHelper()->generateVehicle();
    }

    /**
     * @return VehicleTestHelper
     */
    private function vehicleHelper()
    {
        return (new VehicleTestHelper(FitMotApiClient::create($this->username, $this->password)));
    }

    public function isCertificateUpdated()
    {
        $motTestNumber = $this->motTestHelper->createPassedTest(
            (new \MotFitnesse\Testing\Objects\MotTestCreate())
                ->vehicleId($this->vehicleId)
                ->siteId($this->siteId)
                ->primaryColour($this->oldPrimaryColour)
                ->secondaryColour($this->oldSecondaryColour)
                ->odometerValue((int)$this->oldOdometerReading)
        );

        $result = $this->replacementCertificateHelper->create($motTestNumber);
        $draftId = (int)$result['data']['id'];

        $this->replacementCertificateHelper->update(
            $draftId,
            [
                'primaryColour'   => $this->newPrimaryColour,
                'secondaryColour' => $this->newSecondaryColour,
                'odometerReading' => [
                    'value'      => (int)$this->newOdometerReading,
                    'resultType' => 'OK',
                    'unit'       => 'km'
                ]
            ]
        );
        $response = $this->replacementCertificateHelper->apply($draftId, $this->oneTimePassword);

        if (isset($response['errors'])) {
            return false;
        }

        $motTestData = $this->motTestHelper->getMotTest($motTestNumber);

        $newPrimaryColour = $motTestData['primaryColour']['code'];
        $newSecondaryColour = $motTestData['secondaryColour']['code'];
        $newOdometerReading = $motTestData['odometerReading']['value'];

        return ($newPrimaryColour === $this->newPrimaryColour)
        && ($newSecondaryColour === $this->newSecondaryColour)
        && ($newOdometerReading == $this->newOdometerReading);

    }

    public function setOldPrimaryColour($value)
    {
        $this->oldPrimaryColour = $value;
    }

    public function setOldSecondaryColour($value)
    {
        $this->oldSecondaryColour = $value;
    }

    public function setNewPrimaryColour($value)
    {
        $this->newPrimaryColour = $value;
    }

    public function setNewSecondaryColour($value)
    {
        $this->newSecondaryColour = $value;
    }

    public function setSiteId($value)
    {
        $this->siteId = $value;
    }

    public function setOldOdometerReading($value)
    {
        $this->oldOdometerReading = $value;
    }

    public function setNewOdometerReading($value)
    {
        $this->newOdometerReading = $value;
    }

    public function setOneTimePassword($value)
    {
        $this->oneTimePassword = $value;
    }

    public function setInfoAboutResult($value)
    {
    }
}
