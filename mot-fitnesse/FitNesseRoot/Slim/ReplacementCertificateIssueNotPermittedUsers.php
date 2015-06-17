<?php
require_once 'configure_autoload.php';

use MotFitnesse\Testing\ReplacementCertificateHelper;
use MotFitnesse\Util\TestShared;
use DvsaCommon\Enum\ColourCode;

/**
 * Tests for permissions to replace certs
 */
class ReplacementCertificateIssueNotPermittedUsers
{
    private $testerUsername;
    private $siteId;
    private $oneTimePassword = '123456';

    private $vehicleId;
    private $motTestNumber;
    private $draftId;

    private $username;
    private $password = TestShared::PASSWORD;

    /** @var \TestSupportHelper */
    private $testSupportHelper;

    /** @var MotTestHelper */
    private $motTestHelper;

    /** @var  ReplacementCertificateHelper */
    private $replacementCertificateHelper;
    private $inactiveTesterUsername;
    private $inactiveTesterPassword;

    /*
     * @param string  $testerUsername
     * @param integer $siteId
     */
    public function __construct($testerUsername, $siteId)
    {
        $this->testerUsername = $testerUsername;
        $this->siteId = $siteId;
        $this->motTestHelper = new MotTestHelper(
            new \MotFitnesse\Util\CredentialsProvider(
                $this->testerUsername,
                $this->password
            )
        );
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function setRole($value)
    {
        // illustrative column only
    }

    public function setUsername($value)
    {
        if ($value === 'generated-inactive-tester') {
            $this->username = $this->inactiveTesterUsername;
            $this->password = $this->inactiveTesterPassword;
        } else {
            $this->username = $value;
        }
    }

    public function reset()
    {
        $this->password = TestShared::PASSWORD;
    }

    public function beginTable()
    {
        $this->setupInactiveTester();
        $this->setupVehicle();
        $this->setupPassedTest();
        $this->setupReplacementDraft();
    }

    public function execute()
    {
        $this->replacementCertificateHelper = new ReplacementCertificateHelper($this->username, $this->password);
    }

    public function canGetDraft()
    {
        $result = $this->replacementCertificateHelper->get($this->draftId);

        return $this->isPermissionGranted($result);
    }

    public function canCreateDraft()
    {
        $result = $this->replacementCertificateHelper->create($this->motTestNumber);

        return $this->isPermissionGranted($result);
    }

    public function canUpdateDraft()
    {
        $result = $this->replacementCertificateHelper->update(
            $this->draftId,
            [
                'primaryColour' => ColourCode::BLACK,
            ]
        );

        return $this->isPermissionGranted($result);
    }

    public function canApplyDraft()
    {
        $result = $this->replacementCertificateHelper->apply($this->draftId, $this->oneTimePassword);

        return $this->isPermissionGranted($result);
    }

    private function isPermissionGranted($result)
    {
        $errors = TestShared::errorMessages($result);
        return ($errors !== 'Forbidden');
    }

    private function setupVehicle()
    {
        $vehicleTestHelper = (new VehicleTestHelper(FitMotApiClient::create($this->testerUsername, $this->password)));
        $this->vehicleId = $vehicleTestHelper->generateVehicle();
    }

    private function setupPassedTest()
    {
        $this->motTestNumber = $this->motTestHelper->createPassedTest(
            (new \MotFitnesse\Testing\Objects\MotTestCreate())
                ->vehicleId($this->vehicleId)
                ->siteId($this->siteId)
        );
    }

    private function setupReplacementDraft()
    {
        $replacementCertificateHelper = new ReplacementCertificateHelper($this->testerUsername, $this->password);

        $result = $replacementCertificateHelper->create($this->motTestNumber);
        $this->draftId = (int)$result['data']['id'];
    }

    private function setupInactiveTester()
    {
        $schememgt = $this->testSupportHelper->createSchemeManager();
        $tester = $this->testSupportHelper->createInactiveTester($schememgt['username'], [1]);
        $this->inactiveTesterUsername = $tester['username'];
        $this->inactiveTesterPassword = $tester['password'];
    }
}
