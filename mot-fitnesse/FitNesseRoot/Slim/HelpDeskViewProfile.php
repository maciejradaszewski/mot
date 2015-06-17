<?php
require_once 'configure_autoload.php';

use MotFitnesse\Util\PersonUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Tests for permissions to view other's profiles
 */
class HelpDeskViewProfile
{
    /** @var \TestSupportHelper */
    private $testSupportHelper;

    private $username;
    private $password = TestShared::PASSWORD;
    private $result;
    private $targetPersonId;

    public function __construct()
    {
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function beginTable()
    {
        $this->setupTargetTester();
    }

    public function setRole($value)
    {
        // illustrative column only
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function execute()
    {
        $apiUrl = PersonUrlBuilder::byId($this->targetPersonId)->helpDeskProfile()->toString();

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            $apiUrl,
            TestShared::METHOD_GET,
            null,
            $this->username,
            $this->password
        );

        $this->result = TestShared::execCurlForJson($curlHandle);

    }

    public function canViewOtherUserProfile()
    {
        return TestShared::resultIsSuccess($this->result);
    }

    public function errorMessage()
    {
        return TestShared::errorMessages($this->result);
    }

    private function setupTargetTester()
    {
        $schememgt = $this->testSupportHelper->createSchemeManager();
        $tester = $this->testSupportHelper->createTester($schememgt['username'], [1]);
        $this->targetPersonId = $tester['personId'];
    }
}
