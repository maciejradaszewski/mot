<?php
namespace DvsaMotApiTest\Service;

use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\ReasonForRejection as ReasonForRejectionConstants;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommonTest\TestUtils\Auth\AuthorisationServiceMock;
use DvsaEntities\Entity\BrakeTestResultClass12;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestReasonForRejection;
use DvsaEntities\Entity\MotTestType;
use DvsaEntities\Entity\OdometerReading;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\ReasonForRejection;
use DvsaMotApi\Service\MotTestStatusService;

/**
 * Class MotTestStatusServiceTest
 */
class MotTestStatusServiceTest extends \DvsaCommonApiTest\Service\AbstractServiceTestCase
{
    /**
     * @var MotTestStatusService
     */
    private $sut;

    /** * @var AuthorisationServiceMock */
    private $authorisation;

    public function setUp()
    {
        $this->authorisation = AuthorisationServiceMock::denyAll();
        $this->sut = new MotTestStatusService($this->authorisation);
    }

    public function testIsIncompleteWhenNoOdometerReadingShouldReturnTrue()
    {
        //given
        $motTest = $this->getMotTest(
            [
                'odometerReading' => false
            ]
        );
        $expectedResult = true;

        //when
        $result = $this->sut->isIncomplete($motTest);

        //then
        $this->assertEquals($expectedResult, $result);
    }

    public function testIsIncompleteWhenBrakeTestPerformedShouldReturnFalse()
    {
        //given
        $motTest = $this->getMotTest(
            [
                'odometerReading'    => true,
                'hasBrakeTestResult' => true
            ]
        );
        $expectedResult = false;

        //when
        $result = $this->sut->isIncomplete($motTest);

        //then
        $this->assertEquals($expectedResult, $result);
    }

    public function testIsIncompleteWhenAPersonCannotTestWithoutBrakeTestsShouldReturnFalse()
    {
        //given
        $this->authorisation->granted(PermissionInSystem::TEST_WITHOUT_BRAKE_TESTS);
        $motTest = $this->getMotTest(['odometerReading' => true]);
        $expectedResult = false;

        //when
        $result = $this->sut->isIncomplete($motTest);

        //then
        $this->assertEquals($expectedResult, $result);
    }

    public function testIsIncompleteWhenBrakePerformanceNotTestedRfrShouldReturnFalse()
    {
        //given
        $motTest = $this->getMotTest(
            [
                'odometerReading'                 => true,
                'hasBrakePerformanceNotTestedRfr' => true
            ]
        );
        $expectedResult = false;

        //when
        $result = $this->sut->isIncomplete($motTest);

        //then
        $this->assertEquals($expectedResult, $result);
    }

    public function testIsIncompleteWhenRetestAfterBrakeTestPassedShouldReturnFalse()
    {
        //given
        $motTest = $this->getMotTest(
            [
                'odometerReading'             => true,
                'hasOriginalBrakeTestPassing' => true
            ]
        );
        $expectedResult = false;

        //when
        $result = $this->sut->isIncomplete($motTest);

        //then
        $this->assertEquals($expectedResult, $result);
    }

    private function getMotTest($options)
    {
        $motTest = new MotTest();
        $tester = new Person();
        $motTest->setTester($tester);

        $motTest->setMotTestType((new MotTestType())->setCode(MotTestTypeCode::NORMAL_TEST));

        if (!empty($options['odometerReading'])) {
            $motTest->setOdometerReading(new OdometerReading());
        }

        if (!empty($options['hasBrakeTestResult'])) {
            $motTest->setBrakeTestResultClass12(new BrakeTestResultClass12());
        }

        if (!empty($options['hasBrakePerformanceNotTestedRfr'])) {
            $brakePerformanceNotTestedRfr = new ReasonForRejection();
            $brakePerformanceNotTestedRfr->setRfrId(ReasonForRejectionConstants::CLASS_12_BRAKE_PERFORMANCE_NOT_TESTED_RFR_ID);
            $brakePerformanceNotTestedTestRfr = new MotTestReasonForRejection();
            $brakePerformanceNotTestedTestRfr->setReasonForRejection($brakePerformanceNotTestedRfr);
            $motTest->addMotTestReasonForRejection($brakePerformanceNotTestedTestRfr);
        }

        if (!empty($options['hasOriginalBrakeTestPassing'])) {
            $originalBrakeTest = new BrakeTestResultClass12();
            $originalBrakeTest->setGeneralPass(true);
            $originalMotTest = new MotTest();
            $originalMotTest->setBrakeTestResultClass12($originalBrakeTest);
            $motTest->setMotTestIdOriginal($originalMotTest);

            $motTest->setMotTestType((new MotTestType)->setCode(MotTestTypeCode::RE_TEST));
        }

        return $motTest;
    }
}
