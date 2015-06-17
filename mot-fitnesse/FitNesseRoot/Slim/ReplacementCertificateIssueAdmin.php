<?php

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;

require_once 'configure_autoload.php';

class ReplacementCertificateIssueAdmin
{
    private $username = 'areaoffice1user';
    private $password = TestShared::PASSWORD;
    private $vehicleId = 1;
    private $siteId;
    private $oldPrimaryColour;
    private $newPrimaryColour;
    private $reason;

    private $newVin;
    private $newVrm;

    private $oneTimePassword;
    /** @var MotTestHelper */
    private $motTestHelper;
    /** @var  \MotFitnesse\Testing\ReplacementCertificateHelper */
    private $replacementCertificateHelper;

    /*
     * @param string $testerusername
     */
    public function __construct($testerUsername)
    {
        $this->motTestHelper = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider(
            $testerUsername,
            $this->password
        ));
        $this->replacementCertificateHelper
            = new \MotFitnesse\Testing\ReplacementCertificateHelper($this->username, $this->password);

        $this->vehicleId = $this->vehicleHelper()->generateVehicle(
            [
                'testClass' => VehicleClassCode::CLASS_4,
                'colour'    => 'S',
            ]
        );
    }

    /**
     * @return VehicleTestHelper
     */
    private function vehicleHelper()
    {
        return (new VehicleTestHelper(FitMotApiClient::create(TestShared::USERNAME_TESTER1, TestShared::PASSWORD)));
    }

    public function isCertificateUpdated()
    {
        $motTestId = $this->motTestHelper->createPassedTest(
            (new \MotFitnesse\Testing\Objects\MotTestCreate())
                ->vehicleId($this->vehicleId)
                ->siteId($this->siteId)
                ->primaryColour($this->oldPrimaryColour)
        );

        $result = $this->replacementCertificateHelper->create($motTestId);
        $draftId = (int)$result['data']['id'];

        $this->replacementCertificateHelper->update(
            $draftId,
            [
                'primaryColour'        => $this->newPrimaryColour,
                'vin'                  => $this->newVin,
                'vrm'                  => $this->newVrm,
                'reasonForReplacement' => $this->reason
            ]
        );
        $response = $this->replacementCertificateHelper->apply($draftId, $this->oneTimePassword);
        if (isset($response['errors'])) {
            return false;
        }
        $motTestData = $this->motTestHelper->getMotTest($motTestId);

        $newPrimaryColour = $motTestData['primaryColour']['code'];
        $newVin = $motTestData['vin'];
        $newVrm = $motTestData['registration'];

        return ($newPrimaryColour === $this->newPrimaryColour)
        && ($this->newVin === $newVin)
        && ($this->newVrm === $newVrm);
    }

    public function setOldPrimaryColour($value)
    {
        $this->oldPrimaryColour = $value;
    }

    public function setNewPrimaryColour($value)
    {
        $this->newPrimaryColour = $value;
    }

    public function setNewVin($value)
    {
        $this->newVin = $value;
    }

    public function setNewVrm($value)
    {
        $this->newVrm = $value;
    }

    public function setSiteId($value)
    {
        $this->siteId = $value;
    }

    public function setOneTimePassword($value)
    {
        $this->oneTimePassword = $value;
    }

    public function setReason($value)
    {
        $this->reason = $value;
    }
}
