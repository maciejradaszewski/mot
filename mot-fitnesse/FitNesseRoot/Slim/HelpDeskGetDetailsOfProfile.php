<?php
require_once 'configure_autoload.php';

use MotFitnesse\Util\PersonUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Tests to see if all details of the Person is retrieved
 */
class HelpDeskgetDetailsOfProfile
{
    /** @var \TestSupportHelper */
    private $testSupportHelper;

    private $username;
    private $userType;
    private $password = TestShared::PASSWORD;
    private $result;
    private $targetPersonId;
    private $restricted;

    public function __construct()
    {
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    public function setRestricted($restricted)
    {
        $this->restricted = $restricted;
    }

    public function execute()
    {
        $this->setupCsco();

        switch ($this->userType) {
            case 'tester':
                $this->setupTargetTester();
                break;

            case 'aedm':
                $this->setupTargetAedm();
                break;

            case 'schememgmt':
                $this->setupTargetSchemeMgmt();
                break;

            case 'aed':
                $this->setupTargetAed();
                break;

            case 'ae':
                $this->setupTargetAe();
                break;
        }

        if ($this->restricted == 'false') {
            $apiUrl = PersonUrlBuilder::byId($this->targetPersonId)->helpDeskProfile()->toString();
        } else {
            $apiUrl = PersonUrlBuilder::byId($this->targetPersonId)->helpDeskProfileRestricted()->toString();
        }

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            $apiUrl,
            TestShared::METHOD_GET,
            null,
            $this->username,
            $this->password
        );

        $this->result = TestShared::execCurlForJson($curlHandle);
    }

    public function errorMessage()
    {
        return TestShared::errorMessages($this->result);
    }

    public function targetPersonId()
    {
        return $this->targetPersonId;
    }

    public function cscoUsername()
    {
        return $this->username;
    }

    public function retrievedUserName()
    {
        return (isset($this->result['data']['userName'])) ? 'true' : 'false';
    }

    public function retrievedFirstName()
    {
        return (isset($this->result['data']['firstName'])) ? 'true' : 'false';
    }

    public function retrievedLastName()
    {
        return (isset($this->result['data']['lastName'])) ? 'true' : 'false';
    }

    public function retrievedRoles()
    {
        if (isset($this->result['data']['roles']) &&
            !is_null($this->result['data']['roles'])
        ) {
            $roles = $this->result['data']['roles'];

            if (isset($roles['system'], $roles['sites'], $roles['organisations'])) {
                return 'true';
            } else {
                return 'invalid format';
            }
        } else {
            return 'false';
        }
    }

    private function setupTargetTester()
    {
        $schememgt = $this->testSupportHelper->createSchemeManager();
        $tester = $this->testSupportHelper->createTester($schememgt['username'], [1]);
        $this->targetPersonId = $tester['personId'];
    }

    private function setupTargetAe()
    {
        $user = $this->testSupportHelper->createAuthorisedExaminer(
            $this->testSupportHelper->createAreaOffice1User()['username']
        );

        $this->targetPersonId = $user['id'];
    }

    private function setupTargetAed()
    {
        $this->setupTargetAe();

        $user = $this->testSupportHelper->createAuthorisedExaminerDelegate(
            $this->testSupportHelper->createAreaOffice2User()['username'],
            null,
            [$this->targetPersonId]
        );

        $this->targetPersonId = $user['personId'];
    }

    private function setupTargetAedm()
    {
        $this->setupTargetAe();

        $user = $this->testSupportHelper->createAuthorisedExaminerDesignatedManagement(
            $this->testSupportHelper->createAreaOffice2User()['username'],
            null,
            [$this->targetPersonId]
        );

        $this->targetPersonId = $user['personId'];
    }

    private function setupTargetSchemeMgmt()
    {
        $user = $this->testSupportHelper->createSchemeManager();
        $this->targetPersonId = $user['personId'];
    }

    private function setupCsco()
    {
        $user = $this->testSupportHelper->createCustomerServiceCentreOperative();
        $this->username = $user['username'];
    }
}
