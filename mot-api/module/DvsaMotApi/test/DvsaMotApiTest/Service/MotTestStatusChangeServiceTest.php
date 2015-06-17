<?php

namespace DvsaMotApiTest\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotAuthorizationServiceInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Constants\RfrType;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Date\Time;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\ReasonForRejectionTypeName;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommonApi\Authorisation\Assertion\ApiPerformMotTestAssertion;
use DvsaCommonApiTest\Transaction\TestTransactionExecutor;
use DvsaCommonTest\Date\TestDateTimeHolder;
use DvsaCommonTest\TestUtils\ArgCapture;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\BrakeTestResultClass3AndAbove;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestReasonForCancel;
use DvsaEntities\Entity\MotTestStatus;
use DvsaEntities\Entity\MotTestType;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use DvsaEntities\Entity\SiteTestingDailySchedule;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Entity\VehicleClass;
use DvsaEntities\Repository\EnforcementFullPartialRetestRepository;
use DvsaEntities\Repository\MotTestReasonForCancelRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\MotTestStatusRepository;
use DvsaEntities\Repository\MotTestTypeRepository;
use DvsaEntitiesTest\Entity\WeightSourceFactory;
use DvsaMotApi\Service\CertificateExpiryService;
use DvsaMotApi\Service\Mapper\MotTestMapper;
use DvsaMotApi\Service\MotTestDateHelper;
use DvsaMotApi\Service\MotTestStatusChangeService;
use DvsaMotApi\Service\OtpService;
use DvsaMotApi\Service\TestingOutsideOpeningHoursNotificationService;
use DvsaMotApi\Service\Validator\MotTestStatusChangeValidator;
use DvsaMotApi\Service\Validator\MotTestValidator;
use DvsaMotApiTest\Factory\MotTestObjectsFactory;
use DvsaMotApiTest\Traits\MockTestTypeTrait;
use OrganisationApi\Service\OrganisationService;
use HTMLPurifier;
use DvsaCommonApi\Filter\XssFilter;

/**
 * Class MotTestStatusChangeServiceTest
 *
 * @package DvsaMotApiTest\Service
 */
class MotTestStatusChangeServiceTest extends \PHPUnit_Framework_TestCase
{
    use MockTestTypeTrait;

    const MOT_TEST_ID = 9999;
    const OTP = '123456';

    /** @var MotTestRepository|\PHPUnit_Framework_MockObject_MockObject */
    private $motTestRepository;

    /** @var  MotTestReasonForCancelRepository|\PHPUnit_Framework_MockObject_MockObject */
    private $reasonForCancelRepository;

    /** @var EnforcementFullPartialRetestRepository */
    private $enforcementFullPartialRetestRepository;

    /** @var  AuthorisationServiceInterface */
    private $authService;

    /** @var  MotTestValidator|\PHPUnit_Framework_MockObject_MockObject */
    private $motTestValidator;

    /** @var  MotTestStatusChangeValidator|\PHPUnit_Framework_MockObject_MockObject */
    private $motTestStatusChangeValidator;

    /** @var  OtpService|\PHPUnit_Framework_MockObject_MockObject */
    private $otpService;

    /** @var  OrganisationService|\PHPUnit_Framework_MockObject_MockObject */
    private $organisationService;

    /** @var  MotTestMapper|\PHPUnit_Framework_MockObject_MockObject */
    private $motTestMapper;

    /** @var TestingOutsideOpeningHoursNotificationService $outsideOpeningHoursNotifier */
    private $outsideOpeningHoursNotifier;

    /** @var MotTestDateHelper */
    private $motTestDateHelper;

    /** @var TestDateTimeHolder */
    private $dateTimeHolder;

    private $service;

    private $entityManager;

    public $motTestTypeNormal;

    public $motTestTypeRetest;

    private $motTestTypeRepository;

    /** @var MotTestStatusRepository $motTestStatusRepository */
    private $motTestStatusRepository;

    /** @var callable  */
    private $getRepositoryCallback;

    /** @var  MotIdentityProviderInterface $motIdentityProvider */
    private $motIdentityProvider;

    /** @var ApiPerformMotTestAssertion */
    private $performMotTestAssertion;

    private $expectedUserId = 1;

    protected $xssFilterMock;

    public function setUp()
    {
        $this->dateTimeHolder = new TestDateTimeHolder(new \DateTime('now'));

        $this->authService = XMock::of(\DvsaAuthorisation\Service\AuthorisationServiceInterface::class);
        $this->motTestRepository = XMock::of(MotTestRepository::class);
        $this->reasonForCancelRepository = XMock::of(MotTestReasonForCancelRepository::class);
        $this->enforcementFullPartialRetestRepository = XMock::of(EnforcementFullPartialRetestRepository::class);
        $this->motTestValidator = XMock::of(MotTestValidator::class);
        $this->motTestStatusChangeValidator = XMock::of(MotTestStatusChangeValidator::class);
        $this->otpService = XMock::of(OtpService::class);
        $this->organisationService = XMock::of(OrganisationService::class);
        $this->motTestMapper = XMock::of(MotTestMapper::class);
        $this->outsideOpeningHoursNotifier = XMock::of(TestingOutsideOpeningHoursNotificationService::class);

        $this->motIdentityProvider = XMock::of(MotIdentityProviderInterface::class);
        $this->performMotTestAssertion = XMock::of(ApiPerformMotTestAssertion::class);

        /** @var  MotIdentityInterface $motIdentity */
        $motIdentity = XMock::of(MotIdentityInterface::class);
        $motIdentity->expects($this->any())
            ->method('getUserId')
            ->willReturnCallback(
                function () {
                    return $this->expectedUserId;
                }
            );

        $this->motIdentityProvider->expects($this->any())
            ->method('getIdentity')
            ->willReturn($motIdentity);

        $this->motTestTypeRepository = \DvsaCommonTest\TestUtils\XMock::of(MotTestTypeRepository::class);
        $this->motTestTypeRepository->expects($this->any())
            ->method('__call')
            ->with('findOneByCode')
            ->will($this->returnCallback([$this, 'getMotTestTypeMock']));

        $this->motTestStatusRepository = $this->createMotTestStatusRepository();

        $this->getRepositoryCallback = function ($name) {
            switch ($name) {
                case MotTest::class:
                    return $this->motTestRepository;
                case MotTestStatus::class:
                    return $this->motTestStatusRepository;
                case MotTestType::class:
                    return $this->motTestTypeRepository;
                default:
                    return null;
            }
        };

        $this->entityManager = XMock::of(EntityManager::class);

        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback($this->getRepositoryCallback);

        $this->motTestDateHelper = new MotTestDateHelper(
            XMock::of(CertificateExpiryService::class)
        );

        XMock::mockClassField($this->motTestDateHelper, 'dateTimeHolder', $this->dateTimeHolder);

        $this->xssFilterMock = $this->createXssFilterMock();

        $this->validateMotTestNewStatusResultIs(true);
    }

    public function tearDown()
    {
        TestTransactionExecutor::isFlushed($this->service);
    }

    /**
     * @return MotTestStatusChangeService
     */
    private function createService()
    {
        $this->service = TestTransactionExecutor::inject(
            new MotTestStatusChangeService(
                $this->authService,
                $this->motTestValidator,
                $this->motTestStatusChangeValidator,
                $this->otpService,
                $this->organisationService,
                $this->motTestMapper,
                $this->motTestRepository,
                $this->reasonForCancelRepository,
                $this->enforcementFullPartialRetestRepository,
                $this->outsideOpeningHoursNotifier,
                $this->motTestDateHelper,
                $this->entityManager,
                $this->motIdentityProvider,
                $this->performMotTestAssertion,
                $this->xssFilterMock
            )
        );

        XMock::mockClassField($this->service, 'dateTimeHolder', $this->dateTimeHolder);

        return $this->service;
    }

    /**
     * Data provider for testIssuedDate
     *
     * @return array
     */
    public function dataProviderTestIssuedDate()
    {
        $date = '2014-05-30 23:45:01';
        $expiryDate = '2015-05-29';

        $statusPass = MotTestStatusName::PASSED;
        $statusFail = MotTestStatusName::FAILED;

        return [
            [$statusPass, MotTestTypeCode::NORMAL_TEST, $date, $expiryDate],
            [$statusPass, MotTestTypeCode::RE_TEST, $date, $expiryDate],
            [$statusPass, MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST, $date, $expiryDate],
            [$statusPass, MotTestTypeCode::MOT_COMPLIANCE_SURVEY, $date, null],
            [$statusPass, MotTestTypeCode::TARGETED_REINSPECTION, $date, null],
            [$statusPass, MotTestTypeCode::STATUTORY_APPEAL, $date, $expiryDate],
            [$statusPass, MotTestTypeCode::INVERTED_APPEAL, $date, $expiryDate],
            [$statusFail, MotTestTypeCode::NORMAL_TEST, $date, null],
            [$statusFail, MotTestTypeCode::RE_TEST, $date, null],
            [$statusFail, MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST, $date, null],
            [$statusFail, MotTestTypeCode::MOT_COMPLIANCE_SURVEY, $date, null],
            [$statusFail, MotTestTypeCode::TARGETED_REINSPECTION, $date, null],
            [$statusFail, MotTestTypeCode::STATUTORY_APPEAL, $date, null],
            [$statusFail, MotTestTypeCode::INVERTED_APPEAL, $date, null],
        ];
    }

    /**
     * Test IssuedDate and ExpireDate values should be stored in an mot test depending on the mot test type
     *
     * @param string $status
     * @param string $motTestTypeCode
     * @param string $expectIssuedDate
     * @param string $expectExpiryDate
     *
     * @dataProvider dataProviderTestIssuedDate
     */
    public function testUpdateStatus_givenStatusAndTestType_shouldSetIssuedDateAccordingly(
        $status,
        $motTestTypeCode,
        $expectIssuedDate,
        $expectExpiryDate
    ) {

        //  --  mock MotTest    --
        $motTestId = 1;
        $updateData = [motTestStatusChangeService::FIELD_STATUS => $status];
        $motTestType = (new MotTestType())->setCode($motTestTypeCode);
        $motTest = MotTestObjectsFactory::activeMotTest()->setId(1)->setMotTestType($motTestType);

        //  --  prepare expected values --
        $expectIssuedDate = $expectIssuedDate !== null ? new \DateTime($expectIssuedDate) : null;
        $expectExpiryDate = $expectExpiryDate !== null ? new \DateTime($expectExpiryDate) : null;

        //  --  mock DateTime Holder object --
        $testDate = ($expectIssuedDate === null ? new \DateTime() : $expectIssuedDate);

        $this->motTestResolvesTo($motTest);

        $this->dateTimeHolder->setCurrent($testDate);

        $this->createService()
            ->updateStatus($motTestId, $updateData, 'whatever');

        $this->assertEquals($status, $motTest->getStatus());
        $this->assertEquals($expectIssuedDate, $motTest->getIssuedDate());
        $this->assertEquals($expectExpiryDate, $motTest->getExpiryDate());
    }

    public function testUpdateStatus_givenAbortRequest_setCorrectStatusWithCorrectReason()
    {
        $motTestId = 1;
        $reasonForCancelId = 3;
        $data = [
            motTestStatusChangeService::FIELD_STATUS            => MotTestStatusName::ABORTED,
            MotTestStatusChangeService::FIELD_REASON_FOR_CANCEL => $reasonForCancelId
        ];
        $reasonForCancel = $this->reasonForCancel($reasonForCancelId, false);
        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);
        $this->reasonForCancelResolvesTo($reasonForCancel);
        $this->motTestResolvesTo($motTest);
        $this->otpAuthExpected(false);

        $this->createService()->updateStatus($motTestId, $data, 'whatever');

        $this->assertEquals(MotTestStatusName::ABORTED, $motTest->getStatus());
        $this->assertEquals($reasonForCancel, $motTest->getMotTestReasonForCancel());
    }

    public function testUpdateStatus_givenAbortRequestForNormalTest_shouldReturnSlot()
    {
        $motTestId = 1;
        $reasonForCancelId = 3;
        $data = [
            motTestStatusChangeService::FIELD_STATUS            => MotTestStatusName::ABORTED,
            motTestStatusChangeService::FIELD_REASON_FOR_CANCEL => $reasonForCancelId
        ];
        $reasonForCancel = $this->reasonForCancel($reasonForCancelId, false);
        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);
        $this->reasonForCancelResolvesTo($reasonForCancel);
        $this->motTestResolvesTo($motTest);
        $this->verifySlotReturned();

        $this->createService()->updateStatus($motTestId, $data, 'whatever');
    }

    public function testUpdateStatus_givenAbortRequestForReinspection_shouldNotReturnSlot()
    {
        $this->markTestSkipped();
        $motTestId = 1;
        $reasonForCancelId = 3;
        $data = [
            motTestStatusChangeService::FIELD_STATUS            => MotTestStatusName::ABORTED,
            motTestStatusChangeService::FIELD_REASON_FOR_CANCEL => $reasonForCancelId
        ];
        $reasonForCancel = $this->reasonForCancel($reasonForCancelId, false);
        $motTest = MotTestObjectsFactory::createTest(MotTestTypeCode::TARGETED_REINSPECTION);
        $motTest->setId($motTestId);

        $this->reasonForCancelResolvesTo($reasonForCancel);
        $this->motTestResolvesTo($motTest);
        $this->verifySlotNotReturned();

        $this->createService()->updateStatus($motTestId, $data, 'whatever');
    }

    public function testUpdateStatus_givenAbandonRequest_shouldSetAbandonedStatusWithCorrectCommentAndReason()
    {
        $motTestId = 1;
        $reasonForCancelId = 3;
        $otp = '123456';
        $cancelComment = 'test comment';
        $data = [
            motTestStatusChangeService::FIELD_STATUS            => MotTestStatusName::ABORTED,
            motTestStatusChangeService::FIELD_REASON_FOR_CANCEL => $reasonForCancelId,
            motTestStatusChangeService::FIELD_CANCEL_COMMENT    => $cancelComment,
            motTestStatusChangeService::FIELD_OTP               => $otp
        ];


        $reasonForCancel = $this->reasonForCancel($reasonForCancelId, true);
        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);
        $this->reasonForCancelResolvesTo($reasonForCancel);
        $this->motTestResolvesTo($motTest);
        $this->otpAuthExpected(true, $otp);
        $this->verifySlotReturned();

        $this->createService()->updateStatus($motTestId, $data, 'whatever');

        $this->assertEquals(MotTestStatusName::ABANDONED, $motTest->getStatus());
        $this->assertEquals($reasonForCancel, $motTest->getMotTestReasonForCancel());
        $this->assertEquals($cancelComment, $motTest->getReasonForTerminationComment());
    }

    public function testUpdateStatus_givenPassRequest_shouldSetCorrectStatusAndDates()
    {
        $motTestId = 1;
        $otp = '123456';
        $data = [
            motTestStatusChangeService::FIELD_STATUS => MotTestStatusName::PASSED,
            motTestStatusChangeService::FIELD_OTP    => $otp
        ];

        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);

        $testDate = new \DateTime('2012-09-30');
        $expiryDate = new \DateTime('2013-09-29');

        $this->motTestResolvesTo($motTest);
        $this->otpAuthExpected(true, $otp);

        $this->dateTimeHolder->setCurrent($testDate);

        $this->createService()
            ->updateStatus($motTestId, $data, 'whatever');

        $this->assertEquals($testDate, $motTest->getCompletedDate());
        $this->assertEquals($testDate, $motTest->getIssuedDate());
        $this->assertEquals($expiryDate, $motTest->getExpiryDate());

        $this->assertEquals(MotTestStatusName::PASSED, $motTest->getStatus());
    }

    public function testUpdateStatus_givenPassRequestWithPrs_shouldCreateCorrectPrsMot()
    {
        $motTestId = 1;
        $otp = '123456';
        $data = [
            motTestStatusChangeService::FIELD_STATUS => MotTestStatusName::PASSED,
            motTestStatusChangeService::FIELD_OTP    => $otp
        ];
        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);

        MotTestObjectsFactory::addRfr($motTest, ReasonForRejectionTypeName::PRS);

        $testDate = new \DateTime('2012-09-30');
        $expiryDate = new \DateTime('2013-09-29');

        $this->motTestResolvesTo($motTest);
        $this->otpAuthExpected(true, $otp);

        $this->dateTimeHolder->setCurrent($testDate);

        $this->createService()
            ->updateStatus($motTestId, $data, 'whatever');

        //  --  mot test    --
        $this->assertEquals(MotTestStatusName::FAILED, $motTest->getStatus());
        $this->assertEquals($testDate, $motTest->getCompletedDate());
        $this->assertEquals($testDate, $motTest->getIssuedDate());
        $this->assertEquals(null, $motTest->getExpiryDate());

        //  --  prs mot test    --
        $prsMotTest = $motTest->getPrsMotTest();
        $this->assertNotNull($prsMotTest);
        $this->assertEquals(MotTestStatusName::PASSED, $prsMotTest->getStatus());

        $testDate->add(new \DateInterval('PT1S'));

        $this->assertEquals($testDate, $prsMotTest->getCompletedDate());
        $this->assertEquals($testDate, $prsMotTest->getIssuedDate());
        $this->assertEquals($expiryDate, $prsMotTest->getExpiryDate());
    }

    /**
     * @dataProvider dataProviderTestShouldCreatePrsMot
     */
    public function testUpdateStatusShouldCreatePrsMot($testStatus, $testType, $expectIsPrs)
    {
        //  --  mock date time holder   --
        $testDate = new \DateTime('2012-09-30');
        $this->dateTimeHolder->setCurrent($testDate);

        //  --  create mot test mock    --
        $motTest = MotTestObjectsFactory::activeMotTest()
            ->setId(self::MOT_TEST_ID)
            ->setMotTestType(
                (new MotTestType())->setCode($testType)
            );

        MotTestObjectsFactory::addRfr($motTest, ReasonForRejectionTypeName::PRS);

        //  --  mock other --
        $this->motTestResolvesTo($motTest);
        $this->otpAuthExpected(true, self::OTP);

        //  --  call    --
        $data = [
            motTestStatusChangeService::FIELD_STATUS => $testStatus,
            motTestStatusChangeService::FIELD_OTP    => self::OTP,
        ];

        $this->createService()->updateStatus(self::MOT_TEST_ID, $data, 'whatever');

        //  ----  check   ----
        if ((bool) $expectIsPrs) {
            //  --  mot test    --
            $this->assertEquals(MotTestStatusName::FAILED, $motTest->getStatus());
            $this->assertEquals(null, $motTest->getExpiryDate());

            //  --  mot prs test    --
            $prsMotTest = $motTest->getPrsMotTest();
            $this->assertInstanceOf(MotTest::class, $prsMotTest);
            $this->assertEquals(MotTestStatusName::PASSED, $prsMotTest->getStatus());

        } else {
            //  --  mot test    --
            $this->assertEquals($testStatus, $motTest->getStatus());

            //  --  check prs mot test not created  --
            $this->assertNull($motTest->getPrsMotTest());
        }

        $this->assertEquals(self::MOT_TEST_ID, $motTest->getId());
    }

    public function dataProviderTestShouldCreatePrsMot()
    {
        return [
            [
                'status'      => MotTestStatusName::PASSED,
                'testType'    => MotTestTypeCode::TARGETED_REINSPECTION,
                'expectIsPrs' => false,
            ],
            [MotTestStatusName::PASSED, MotTestTypeCode::MOT_COMPLIANCE_SURVEY, false],
            [MotTestStatusName::FAILED, MotTestTypeCode::NORMAL_TEST, false],
            [MotTestStatusName::PASSED, MotTestTypeCode::NORMAL_TEST, true],
            [MotTestStatusName::PASSED, MotTestTypeCode::RE_TEST, true],
            [MotTestStatusName::PASSED, MotTestTypeCode::INVERTED_APPEAL, true],
            [MotTestStatusName::PASSED, MotTestTypeCode::STATUTORY_APPEAL, true],
            [MotTestStatusName::PASSED, MotTestTypeCode::PARTIAL_RETEST_LEFT_VTS, true],
            [MotTestStatusName::PASSED, MotTestTypeCode::PARTIAL_RETEST_REPAIRED_AT_VTS, true],
            [MotTestStatusName::PASSED, MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST, true],
            [MotTestStatusName::PASSED, MotTestTypeCode::NON_MOT_TEST, true],
            [MotTestStatusName::PASSED, MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING, true],
        ];
    }

    public function testUpdateStatus_givenPassRequestWithPrs_shouldRetainAdvisoryRfrsOnPassedTest()
    {
        $motTestId = 1;
        $otp = '123456';
        $data = [
            motTestStatusChangeService::FIELD_STATUS => MotTestStatusName::PASSED,
            motTestStatusChangeService::FIELD_OTP    => $otp
        ];
        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);

        MotTestObjectsFactory::addRfr($motTest, ReasonForRejectionTypeName::PRS);
        MotTestObjectsFactory::addRfr($motTest, ReasonForRejectionTypeName::ADVISORY);
        MotTestObjectsFactory::addRfr($motTest, ReasonForRejectionTypeName::ADVISORY);

        $testDate = new \DateTime('2012-09-30');
        $expiryDate = new \DateTime('2013-09-29');

        $this->motTestResolvesTo($motTest);
        $this->otpAuthExpected(true, $otp);

        $this->dateTimeHolder->setCurrent($testDate);

        $this->createService()
            ->updateStatus($motTestId, $data, 'whatever');

        //  --  mot test    --
        $this->assertEquals(MotTestStatusName::FAILED, $motTest->getStatus());
        $this->assertEquals($testDate, $motTest->getCompletedDate());
        $this->assertEquals($testDate, $motTest->getIssuedDate());
        $this->assertNull($motTest->getExpiryDate());
        $this->assertCount(2, $motTest->getMotTestReasonForRejectionsOfType(ReasonForRejectionTypeName::ADVISORY));

        //  --  prs mot test    --
        $prsMotTest = $motTest->getPrsMotTest();
        $this->assertNotNull($prsMotTest);
        $this->assertEquals(MotTestStatusName::PASSED, $prsMotTest->getStatus());
        $testDate->add(new \DateInterval('PT1S'));
        $this->assertEquals($testDate, $prsMotTest->getCompletedDate());
        $this->assertEquals($testDate, $prsMotTest->getIssuedDate());
        $this->assertEquals($expiryDate, $prsMotTest->getExpiryDate());
    }

    public function testUpdateStatus_givenFailedRequest_shouldSetRightData()
    {
        $motTestId = 1;
        $otp = '123456';
        $data = [
            motTestStatusChangeService::FIELD_STATUS => MotTestStatusName::FAILED,
            motTestStatusChangeService::FIELD_OTP    => $otp
        ];
        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);

        MotTestObjectsFactory::addRfr($motTest, ReasonForRejectionTypeName::PRS);

        $this->motTestResolvesTo($motTest);
        $this->otpAuthExpected(true, $otp);

        $testDate = new \DateTime('2012-09-30');
        $this->dateTimeHolder->setCurrent($testDate);

        $this->createService()
            ->updateStatus($motTestId, $data, 'whatever');

        $this->assertEquals($testDate, $motTest->getIssuedDate());
        $this->assertNull($motTest->getExpiryDate());
        $this->assertEquals(MotTestStatusName::FAILED, $motTest->getStatus());
    }

    public function testUpdateStatus_givenStatusAbortedVe_shouldUpdateStatusSetCommentReturnSlot()
    {
        $reasonForTerminationComment = 'this is a test reason';
        $motTestId = 1;
        $data = [
            motTestStatusChangeService::FIELD_STATUS           => MotTestStatusName::ABORTED_VE,
            motTestStatusChangeService::FIELD_REASON_FOR_ABORT => $reasonForTerminationComment
        ];

        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);
        $this->motTestResolvesTo($motTest);
        $this->verifySlotReturned();

        $this->dateTimeHolder->setCurrent(new \DateTime());
        $this->createService()->updateStatus($motTestId, $data, 'whatever');

        $this->assertEquals(MotTestStatusName::ABORTED_VE, $motTest->getStatus());
        $this->assertEquals($reasonForTerminationComment, $motTest->getReasonForTerminationComment());

        $this->assertNull($motTest->getCompletedDate());
        $this->assertEquals($this->dateTimeHolder->getCurrent(), $motTest->getIssuedDate());

        $this->assertNull($motTest->getExpiryDate());
    }

    public function testUpdateStatus_whenPassingVehicleAndVsiWeightType_shouldUpdateVehicleWeight()
    {
        $motTestId = 1;
        $otp = '123456';
        $data = [
            motTestStatusChangeService::FIELD_STATUS => MotTestStatusName::PASSED,
            motTestStatusChangeService::FIELD_OTP    => $otp
        ];
        list($oldWeight, $newWeight) = [123456, 654321];

        $motTest = MotTestObjectsFactory::activeMotTest()
            ->setId($motTestId)
            ->setVehicleClass(new VehicleClass(VehicleClassCode::CLASS_4));

        self::addBrakeTestResultWithUpdatableVehicleWeight($motTest, $oldWeight, $newWeight, WeightSourceFactory::vsi());
        $this->motTestResolvesTo($motTest);

        $this->createService()->updateStatus($motTestId, $data, 'whatever');

        $this->assertEquals($newWeight, $motTest->getVehicle()->getWeight());
    }

    /**
     * @return array
     */
    public function dataProvider_shouldUpdateVehicleWeightOnlyForCertainClassAndWeightType()
    {
        return [
            [Vehicle::VEHICLE_CLASS_1, WeightSourceFactory::vsi(), false],
            [Vehicle::VEHICLE_CLASS_2, WeightSourceFactory::vsi(),false],
            [Vehicle::VEHICLE_CLASS_3, WeightSourceFactory::vsi(),true],
            [Vehicle::VEHICLE_CLASS_4, WeightSourceFactory::vsi(), true],
            [Vehicle::VEHICLE_CLASS_5, WeightSourceFactory::vsi(), false],
            [Vehicle::VEHICLE_CLASS_7, WeightSourceFactory::vsi(), false],
            [Vehicle::VEHICLE_CLASS_1, WeightSourceFactory::dgw(), false],
            [Vehicle::VEHICLE_CLASS_2, WeightSourceFactory::dgw(),false],
            [Vehicle::VEHICLE_CLASS_3, WeightSourceFactory::dgw(),false],
            [Vehicle::VEHICLE_CLASS_4, WeightSourceFactory::dgw(), false],
            [Vehicle::VEHICLE_CLASS_5, WeightSourceFactory::dgw(), true],
            [Vehicle::VEHICLE_CLASS_7, WeightSourceFactory::dgw(), true],
        ];
    }

    /**
     * @param $class
     * @param $weightType
     * @param $isUpdated
     *
     * @dataProvider dataProvider_shouldUpdateVehicleWeightOnlyForCertainClassAndWeightType
     */
    public function testUpdateStatus_whenPassingVehicleAndVsiWeightType_shouldUpdateVehicleWeightOnlyForCertainClass(
        $class,
        $weightType,
        $isUpdated
    ) {
        $motTestId = 1;
        $otp = '123456';
        $data = [
            motTestStatusChangeService::FIELD_STATUS => MotTestStatusName::PASSED,
            motTestStatusChangeService::FIELD_OTP    => $otp
        ];

        list($oldWeight, $newWeight) = [123456, 654321];

        $motTest = MotTestObjectsFactory::activeMotTest()
            ->setId($motTestId)
            ->setVehicleClass(new VehicleClass($class));

        self::addBrakeTestResultWithUpdatableVehicleWeight($motTest, $oldWeight, $newWeight, $weightType);

        $this->motTestResolvesTo($motTest);

        $this->createService()->updateStatus($motTestId, $data, 'whatever');

        $this->assertEquals($isUpdated, $newWeight === $motTest->getVehicle()->getWeight());
    }

    /**
     *
     * @return array
     */
    public function dataProvider_givenTestFailedOutsideSiteOpeningHours_shouldNotify()
    {
        return [
            [MotTestStatusName::PASSED, MotTestStatusName::FAILED]
        ];
    }

    /**
     * @param string $status
     *
     * @dataProvider dataProvider_givenTestFailedOutsideSiteOpeningHours_shouldNotify
     */
    public function testUpdateStatus_givenTestFailedOutsideSiteOpeningHours_shouldNotify($status)
    {
        $siteBusRoleMap = new SiteBusinessRoleMap();
        $siteBusRoleMap->setPerson(new Person());

        $repo = $this->getMock('\stdClass', ['findOneBy']);
        $repo->expects($this->any())
                        ->method('findOneBy')
                        ->will($this->returnValue($siteBusRoleMap));

        $getRepositoryCallback = $this->getRepositoryCallback;

        $this->entityManager = XMock::of('Doctrine\ORM\EntityManager');
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnCallback(function ($name) use ($repo, $getRepositoryCallback) {
                if ($repository = $getRepositoryCallback($name)) {
                    return $repository;
                }

                return $repo;
            }));

        $motTestId = 1;
        $otp = '123456';
        $data = [
            motTestStatusChangeService::FIELD_STATUS => $status,
            motTestStatusChangeService::FIELD_OTP    => $otp
        ];

        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);
        MotTestObjectsFactory::addTestAuthorisationForClass(
            $motTest,
            "4",
            AuthorisationForTestingMotStatusCode::QUALIFIED
        );
        self::addSiteOpeningHours($motTest);
        $dateHolder = new TestDateTimeHolder(DateUtils::toDateTime('2012-09-30T16:00:01Z'));
        $this->motTestResolvesTo($motTest);
        list($siteCap, $personCap, $completionDateCap) = $this->notificationOnTestOutsideOpeningHoursExpected();

        $this->createService()->setDateTimeHolder($dateHolder)->updateStatus($motTestId, $data, "whatever");

        $this->assertEquals($motTest->getVehicleTestingStation(), $siteCap->get());
        $this->assertEquals($motTest->getTester(), $personCap->get());
        $this->assertEquals($motTest->getCompletedDate(), $completionDateCap->get());
    }

    private function helper_givenConfirmRequestByAnotherUser_ShouldThrowError($status)
    {
        $this->setExpectedUserId(2);

        $motTestId = 1;
        $data = [
            motTestStatusChangeService::FIELD_STATUS => $status,
        ];

        $motTest = MotTestObjectsFactory::activeMotTest()->setId($motTestId);

        $this->motTestResolvesTo($motTest);

        try {
            $this->createService()
                ->updateStatus($motTestId, $data, 'whatever');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'This test was started by another user and you are not allowed to confirm its result');
        }

        $this->assertEquals(MotTestStatusName::ACTIVE, $motTest->getStatus());
    }

    public function testUpdateStatus_givenPassRequestByAnotherUser_ShouldThrowError()
    {
        $this->helper_givenConfirmRequestByAnotherUser_ShouldThrowError(MotTestStatusName::PASSED);
    }

    public function testUpdateStatus_givenFailRequestByAnotherUser_ShouldThrowError()
    {
        $this->helper_givenConfirmRequestByAnotherUser_ShouldThrowError(MotTestStatusName::FAILED);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createXssFilterMock()
    {
        $xssFilterMock = $this
            ->getMockBuilder(XssFilter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $xssFilterMock
            ->method('filter')
            ->will($this->returnArgument(0));
        $xssFilterMock
            ->method('filterMultiple')
            ->will($this->returnArgument(0));

        return $xssFilterMock;
    }

    private static function addSiteOpeningHours(MotTest $motTest)
    {
        list($openingTime, $closingTime) = [Time::fromIso8601("08:00:00"), Time::fromIso8601("16:00:00")];
        $weekOpeningHours = [];
        for ($i = 1; $i <= 7; $i++) {
            $dailySchedule = (new SiteTestingDailySchedule())
                ->setOpenTime($openingTime)
                ->setCloseTime($closingTime)
                ->setWeekday($i);
            $weekOpeningHours [] = $dailySchedule;
        }
        $motTest->getVehicleTestingStation()->setSiteTestingSchedule($weekOpeningHours);
    }

    private static function addBrakeTestResultWithUpdatableVehicleWeight(MotTest $motTest, $oldWeight, $newWeight, $newWeightType)
    {
        $brakeTestResult = (new BrakeTestResultClass3AndAbove())
            ->setWeightType($newWeightType)
            ->setVehicleWeight($newWeight);
        $motTest->setBrakeTestResultClass3AndAbove($brakeTestResult)
            ->setVehicleClass($motTest->getVehicleClass())
            ->getVehicle()->setWeight($oldWeight);
    }

    private function validateMotTestNewStatusResultIs($response)
    {
        $this->motTestStatusChangeValidator->expects($this->any())->method(
            'verifyThatStatusTransitionIsPossible'
        )
            ->will($this->returnValue($response));
    }

    private function motTestResolvesTo(MotTest $motTest)
    {
        $this->motTestRepository->expects($this->atLeastOnce())
            ->method('getMotTestByNumber')->will($this->returnValue($motTest));
    }

    private function reasonForCancelResolvesTo(MotTestReasonForCancel $reasonForCancel)
    {
        $this->reasonForCancelRepository->expects($this->atLeastOnce())
            ->method('get')->with($reasonForCancel->getId())->will($this->returnValue($reasonForCancel));
    }

    private function otpAuthExpected($isExpected, $token = null)
    {
        $def = $this->otpService->expects($isExpected ? $this->atLeastOnce() : $this->never())->method('authenticate');
        if ($token) {
            $def->with($token);
        }
    }

    private function reasonForCancel($id, $isAbandoned)
    {
        return (new MotTestReasonForCancel())->setAbandoned($isAbandoned)->setId($id)->setReason("reason");
    }

    private function notificationOnTestOutsideOpeningHoursExpected()
    {
        list($site, $person, $completionDate) = [ArgCapture::create(), ArgCapture::create(), ArgCapture::create()];
        $this->outsideOpeningHoursNotifier->expects($this->once())
            ->method("notify")->with($site(), $person(), $completionDate());

        return [$site, $person, $completionDate];
    }

    private function verifySlotReturned()
    {
        $this->organisationService->expects($this->once())->method('incrementSlotBalance');
    }

    private function verifySlotNotReturned()
    {
        $this->organisationService->expects($this->never())->method('incrementSlotBalance');
    }

    private function setExpectedUserId($userId)
    {
        return $this->expectedUserId = $userId;
    }

    private function createMotTestStatusRepository()
    {
        $createStatusCallback = function ($name) {
            $motTestStatus = XMock::of(MotTestStatus::class);
            $motTestStatus
                ->expects($this->any())
                ->method("getName")
                ->willReturn($name);

            return $motTestStatus;
        };

        $repository = XMock::of(MotTestStatusRepository::class);
        $repository
            ->expects($this->any())
            ->method("findByName")
            ->willReturnCallback(function ($name) use ($createStatusCallback) {
                return $createStatusCallback($name);
            });

        return $repository;
    }
}
