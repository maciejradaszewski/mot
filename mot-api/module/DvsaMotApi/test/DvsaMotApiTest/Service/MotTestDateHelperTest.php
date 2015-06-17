<?php

namespace DvsaMotApiTest\Service;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\Date\TestDateTimeHolder;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\MotTestStatus;
use DvsaMotApi\Service\CertificateExpiryService;
use DvsaMotApi\Service\MotTestDateHelper;
use DvsaMotApi\Service\MotTestService;
use DvsaMotApiTest\Factory\MotTestObjectsFactory;
use DvsaMotApiTest\Factory\VehicleObjectsFactory;

/**
 * Class MotTestDateHelperTest
 *
 * @package DvsaMotApiTest\Service
 */
class MotTestDateHelperTest extends AbstractServiceTestCase
{
    const TEST_ISSUED_DATE = '2014-05-01';
    const TEST_EXPIRY_DATE = '2015-04-30';

    const CURRENT_DATE = '2014-05-01';

    const TEST_STATUS_PASS = 1;
    const TEST_STATUS_PENDING_PASS = 2;
    const TEST_STATUS_FAIL = 3;
    const TEST_STATUS_INCOMPLETE = 4;

    private $currentDateTime;

    public function setUp()
    {
        $this->currentDateTime = DateUtils::toDate(self::CURRENT_DATE);
    }

    /**
     * @param int|null $status       MotTest status Passed or Pending Passed
     * @param string   $issuedDate   MotTest issued date, if null take from MotTest
     * @param int      $testType     MotTest type
     * @param string   $expectResult Expected Date
     * @param boolean  $emergency    Emergency test
     *
     * @dataProvider dataProviderTestIssuedDate
     */
    public function testIssuedDate(
        $status,
        $issuedDate,
        $testType,
        $expectResult,
        $emergency = false
    ) {
        $issuedDate = $issuedDate ? DateUtils::toDate($issuedDate) : $issuedDate;
        $expectResult = $expectResult ? DateUtils::toDate($expectResult) : null;

        //  --  mock MotTest    --
        $motTest = $this->getMockMotTest($testType, $status);

        if ($emergency === true) {
            $motTest->setEmergencyLog($emergency);
        }

        $pendingStatus = null;
        if ($status === self::TEST_STATUS_PENDING_PASS) {
            $pendingStatus = MotTestStatusName::PASSED;
        } elseif ($status === self::TEST_STATUS_INCOMPLETE) {
            $pendingStatus = MotTestService::PENDING_INCOMPLETE_STATUS;
        }

        //  --  prepare checked service --
        $motTestDateHelper = $this->getMockMotTestDateHelper();

        $result = $motTestDateHelper->getIssuedDate($motTest, $issuedDate, $pendingStatus);

        $this->assertEquals($expectResult, $result);//  , 'Unexpected result');
    }

    public function dataProviderTestIssuedDate()
    {
        $issuedDate = '2014-01-31';

        return [
            [
                'status'       => null,
                'issuedDate'   => null,
                'testType'     => MotTestTypeCode::NORMAL_TEST,
                'expectResult' => self::CURRENT_DATE,
            ],
            [null, $issuedDate, MotTestTypeCode::NORMAL_TEST, $issuedDate],
            [null, $issuedDate, MotTestTypeCode::NON_MOT_TEST, $issuedDate],

            [self::TEST_STATUS_FAIL, null, MotTestTypeCode::NORMAL_TEST, self::CURRENT_DATE],
            [self::TEST_STATUS_FAIL, $issuedDate, MotTestTypeCode::NORMAL_TEST, $issuedDate],

            [self::TEST_STATUS_FAIL, null, MotTestTypeCode::MOT_COMPLIANCE_SURVEY, self::CURRENT_DATE],
            [self::TEST_STATUS_FAIL, null, MotTestTypeCode::TARGETED_REINSPECTION, self::CURRENT_DATE],

            [self::TEST_STATUS_PASS, null, MotTestTypeCode::NORMAL_TEST, self::CURRENT_DATE],
            [self::TEST_STATUS_PENDING_PASS, null, MotTestTypeCode::NORMAL_TEST, self::CURRENT_DATE],
            [self::TEST_STATUS_PENDING_PASS, $issuedDate, MotTestTypeCode::NORMAL_TEST, $issuedDate],

            [self::TEST_STATUS_INCOMPLETE, $issuedDate, MotTestTypeCode::NORMAL_TEST, null],

            [self::TEST_STATUS_PASS, null, MotTestTypeCode::NORMAL_TEST, self::CURRENT_DATE, true],
        ];
    }

    /**
     * @param int|null $status       MotTest status Passed or Pending Passed
     * @param string   $issuedDate   MotTest issued date, if null take from MotTest
     * @param int      $testType     MotTest type
     * @param string   $expectResult Expected Date
     *
     * @dataProvider dataProviderTestExpiryDate
     */
    public function testExpiryDate(
        $status,
        $issuedDate,
        $testType,
        $expectResult,
        $expectException = null
    ) {
        //  --  mock MotTest    --
        $motTest = $this->getMockMotTest($testType, $status);

        $pendingStatus = null;
        if ($status == self::TEST_STATUS_PENDING_PASS) {
            $pendingStatus = MotTestStatusName::PASSED;
        }

        //  --  prepare checked service --
        $motTestDateHelper = new MotTestDateHelper(
            $this->getMockCertificateExpiryService()
        );

        $issuedDate = $issuedDate ? DateUtils::toDate($issuedDate) : $issuedDate;
        $expectResult = $expectResult ? DateUtils::toDate($expectResult) : null;

        if ($expectException) {
            $this->setExpectedException($expectException);
        }

        $result = $motTestDateHelper->getExpiryDate($motTest, $issuedDate, $pendingStatus);

        $this->assertEquals($expectResult, $result, 'Unexpected result');
    }

    public function dataProviderTestExpiryDate()
    {
        $issuedDate = '2014-01-31';
        $expiryDate = '2015-01-30';

        return [
            //  --  test not passed -> expire date = null   --
            [
                'testStatus'     => null,
                'issuedDate'     => null,
                'testType'       => MotTestTypeCode::NORMAL_TEST,
                'expectedResult' => null
            ],
            [null, $issuedDate, MotTestTypeCode::NORMAL_TEST, null],

            //  --  test pending or finished passed  - NORMAL --
            [self::TEST_STATUS_PASS, null, MotTestTypeCode::NORMAL_TEST, self::TEST_EXPIRY_DATE],
            [self::TEST_STATUS_PASS, $issuedDate, MotTestTypeCode::NORMAL_TEST, $expiryDate],
            [self::TEST_STATUS_PENDING_PASS, $issuedDate, MotTestTypeCode::NORMAL_TEST, $expiryDate],

            //  --  test pending or finished passed  - NON-MOT and other --
            [self::TEST_STATUS_PASS, null, MotTestTypeCode::MOT_COMPLIANCE_SURVEY, null],
            [self::TEST_STATUS_PASS, null, MotTestTypeCode::TARGETED_REINSPECTION, null],

            [self::TEST_STATUS_PENDING_PASS, null, MotTestTypeCode::NORMAL_TEST, null, 'Exception'],
        ];
    }

    /**
     * @dataProvider dataProviderTestExpiryDateByCertificate
     */
    public function testExpiryDateByCertificate(
        $issuedDate,
        $certificateExpiryDate,
        $certificateIsEarlier,
        $expectResult
    ) {
        $issuedDate = $issuedDate ? DateUtils::toDate($issuedDate) : $issuedDate;
        $expectResult = $expectResult ? DateUtils::toDate($expectResult) : null;

        //  --  mock MotTest    --
        $motTest = $this->getMockMotTest();

        //  --  mock Services   --
        $certificateResult = null;
        if ($certificateExpiryDate !== null || $certificateIsEarlier !== null) {
            $certificateResult = [
                'expiryDate'                 => $certificateExpiryDate,
                'isEarlierThanTestDateLimit' => $certificateIsEarlier
            ];
        }
        $mockCertExpireService = $this->getMockCertificateExpiryService($certificateResult);

        //  --  prepare checked service --
        $motTestDateHelper = new MotTestDateHelper($mockCertExpireService);

        $result = $motTestDateHelper->getExpiryDate($motTest, $issuedDate);

        $this->assertEquals($expectResult, $result, 'Unexpected result');
    }

    public function dataProviderTestExpiryDateByCertificate()
    {
        return [
            [
                'issuedDate'            => '2014-05-07',
                'certificateExpiryDate' => '2015-05-10',
                'certificateIsEarlier'  => true,
                'newExpiryDate'         => '2015-05-06'
            ],
            ['2014-05-07', '2014-05-01', true, '2015-05-06'],

            ['2014-05-07', '2014-05-10', false, '2015-05-10'],
            ['2014-05-07', '2014-05-01', false, '2015-05-06'],

            ['2014-05-07', null, null, '2015-05-06'],
        ];
    }

    private function getMockMotTestDateHelper($currentDateTime = null)
    {
        if (!($currentDateTime instanceof \DateTime)) {
            $currentDateTime = $this->currentDateTime;
        }

        $motTestDateHelper = new MotTestDateHelper(
            $this->getMockCertificateExpiryService()
        );

        $this->mockClassField($motTestDateHelper, 'dateTimeHolder', new TestDateTimeHolder($currentDateTime));

        return $motTestDateHelper;
    }

    private function getMockCertificateExpiryService($checkResult = null)
    {
        $mock = $this->getMockWithDisabledConstructor(CertificateExpiryService::class);

        $mock->expects($this->any())
            ->method("getExpiryDetailsForVehicle")
            ->will($this->returnValue($checkResult));

        return $mock;
    }

    private function getMockMotTest(
        $testTypeCode = MotTestTypeCode::NORMAL_TEST,
        $status = self::TEST_STATUS_PASS
    ) {
        $testType = (new \DvsaEntities\Entity\MotTestType())->setCode($testTypeCode);
        $motTest = MotTestObjectsFactory::activeMotTest()
            ->setId(1)
            ->setMotTestType($testType)
            ->setVehicle(VehicleObjectsFactory::vehicle(4))
            ->setCompletedDate($this->currentDateTime)
            ->setStatus($this->createMotTestActiveStatus());

        if ($status == self::TEST_STATUS_PASS) {
            $motTest
                ->setStatus($this->createMotTestPassedStatus())
                ->setIssuedDate(DateUtils::toDate(self::TEST_ISSUED_DATE));
        } elseif ($status == self::TEST_STATUS_FAIL) {
            $motTest
                ->setStatus($this->createMotTestFailedStatus());
        }

        return $motTest;
    }

    private function createMotTestActiveStatus()
    {
        return $this->createMotTestStatus(MotTestStatusName::ACTIVE);
    }

    private function createMotTestPassedStatus()
    {
        return $this->createMotTestStatus(MotTestStatusName::PASSED);
    }

    private function createMotTestFailedStatus()
    {
        return $this->createMotTestStatus(MotTestStatusName::FAILED);
    }

    private function createMotTestStatus($name)
    {
        $status = XMock::of(MotTestStatus::class);
        $status
            ->expects($this->any())
            ->method("getName")
            ->willReturn($name);

        return $status;
    }
}
