<?php

namespace DvsaMotApiTest\Controller\Validator;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\EmergencyReasonCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaEntities\Entity\EmergencyLog;
use DvsaEntities\Entity\Person;
use DvsaMotApi\Controller\Validator\EmergencyLogValidator;
use DvsaMotApi\Service\EmergencyService;
use SiteApi\Service\SiteService;
use PersonApi\Service\PersonService;
use Zend\ServiceManager\ServiceManager;

class EmergencyLogValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** AT least this many characters for the reason code are required */
    const MIN_REASON_LENGTH  = 5;

    /** @var  EmergencyLogValidator */
    protected $elv;

    /** @var ServiceManager */
    protected $serviceManager;

    /** @var  \SiteApi\Service\SiteService */
    protected $mockSiteService;

    /** \PersonApi\Service */
    protected $mockPersonService;

    /** @var  \DvsaMotApi\Service\EmergencyService */
    protected $mockEmergencyService;

    /** @var  EmergencyLog */
    protected $emergencyLog;

    /** @var \DvsaEntities\Entity\Person */
    protected $mockPerson;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        $this->mockSiteService = \DvsaCommonTest\TestUtils\XMock::of(
            SiteService::class,
            ['getSite']
        );

        $this->mockEmergencyService = \DvsaCommonTest\TestUtils\XMock::of(
            EmergencyService::class,
            ['getEmergencyLog']
        );


        $this->mockPersonService = \DvsaCommonTest\TestUtils\XMock::of(
            PersonService::class,
            ['getPersonByIdentifier']
        );

        $this->mockPerson = \DvsaCommonTest\TestUtils\XMock::of(
            Person::class,
            ['isQualifiedTester']
        );
        $this->mockPersonService->expects($this->any())
            ->method('getPersonByIdentifier')
            ->with($this->anything(), true)
            ->willReturn($this->mockPerson);


        $this->serviceManager->setService(PersonService::class, $this->mockPersonService);
        $this->serviceManager->setService(EmergencyService::class, $this->mockEmergencyService);
        $this->serviceManager->setService(SiteService::class, $this->mockSiteService);
        $this->elv = new EmergencyLogValidator($this->serviceManager);
    }

    protected function setQualifiedTester($mode)
    {
        $this->mockPerson->expects($this->any())->method('isQualifiedTester')->willReturn($mode);
    }

    public function testAllowsEmptyConstructionForDelayedValidation()
    {
        $v = new EmergencyLogValidator($this->serviceManager, null);

        $this->assertFalse($v->isValidated());
        $this->assertFalse($v->isValid());
    }

    /**
     * @dataProvider missingFieldProvider
     * @param $missing String the name of the field not supplied in data set
     * @param $dataSet Array contains test data
     */
    public function testFailsValidationOnMissingFields($missing, $errorMessage, $dataSet)
    {
        $this->setQualifiedTester(true);

        // when a tester is being performed by self
        if ($missing == 'tester_code' && ArrayUtils::tryGet($dataSet, 'tested_by_whom') == 'current') {
            return;
        }

        if ('contingency_code' != $missing) {
            $this->mockEmergencyService->expects($this->once())
                ->method('getEmergencyLog')
                ->willReturn(isset($dataSet['contingency_code']));
        }

        $this->mockSiteService->expects($this->any())
            ->method('getSite')
            ->willReturn(new \stdClass());

        $this->assertFalse($this->elv->validate($dataSet));
        $errors = $this->elv->getErrorMsg();

        $this->assertEquals(2, count($errors), 'not right number of fails. ' . var_export($errors, true));
        $this->assertEquals($errorMessage, $errors[0]->getMessage());
    }

    public function missingFieldProvider()
    {
        return array_map(
            [$this, 'dpForMissing'],
            ['contingency_code', 'tested_by_whom', 'tester_code', 'site_id', 'reason_code', 'reason_text']
        );
    }

    public function testValidationFailsWhenReasonCodeTooShort()
    {
        $reqData = [
            'contingency_code' => '123456A',
            'reason_code'      => EmergencyReasonCode::OTHER,
            'reason_text'      => 'four',
            'tester_code'      => 'SEAN0001',
            'tested_by_whom'   => 'current',
            'site_id'          => 345,
            'test_date'        => DateUtils::today(),
            'test_date_year'   => '2014',
            'test_date_month'  => '01',
            'test_date_day'    => '01',
        ];


        $this->emergencyLog = \DvsaCommonTest\TestUtils\XMock::of(
            EmergencyLog::class
        );
        $this->mockEmergencyService->expects($this->any())
            ->method('getEmergencyLog')
            ->with($this->anything())
            ->willReturn($this->emergencyLog);

        $this->mockSiteService->expects($this->any())
            ->method('getSite')
            ->with(345)
            ->willReturn(new \stdClass());

        $validationReturn = $this->elv->validate($reqData);
        $errorMessages    = $this->elv->getErrorMsg();
        $this->assertFalse(
            $validationReturn,
            'Validation return did not fail. Error messages: ' . var_export($errorMessages, true)
        );
        $this->assertEquals(
            1,
            count($errorMessages),
            'Validation return did not include expected number of messages. Error messages: ' . var_export(
                $errorMessages,
                true
            )
        );
        $this->assertEquals(
            EmergencyLogValidator::ERR_REASON_LENGTH,
            $errorMessages[0]->getDisplayMessage(),
            'Error message not as expected'
        );
    }

    public function testTesterCodeIgnoredWhenTestedByOther()
    {
        $reqData = [
            'contingency_code' => '123456A',
            'reason_code'      => EmergencyReasonCode::COMMUNICATION_PROBLEM,
            'tester_code'      => 'SEAN0001',
            'tested_by_whom'   => 'current',
            'site_id'          => 345,
            'test_date'        => DateUtils::today(),
            'test_date_year'   => '2014',
            'test_date_month'  => '01',
            'test_date_day'    => '01',
        ];


        $this->emergencyLog = \DvsaCommonTest\TestUtils\XMock::of(
            EmergencyLog::class
        );
        $this->mockEmergencyService->expects($this->any())
            ->method('getEmergencyLog')
            ->with($this->anything())
            ->willReturn($this->emergencyLog);

        $this->mockSiteService->expects($this->any())
            ->method('getSite')
            ->with(345)
            ->willReturn(new \stdClass());

        $validationReturn = $this->elv->validate($reqData);
        $this->assertTrue($validationReturn, 'Validation return was not true. Error messages: ' . var_export($this->elv->getErrorMsg(), true));
    }

    /**
     * Helper: Returns a full DTO transfer array minus one optional  key.
     *
     * @param  $fieldName String contains a field for removal
     * @return array
     */
    protected function dtoMinus($fieldName = null)
    {
        $testDate    = new \DateTime;
        $testDate->setDate(2014, 1, 1);
        $dtoData
            = [
            'contingency_code' => '123456A',
            'tested_by_whom'   => 'current',
            'tester_code'      => 'SEAN0001',
            'test_date'        => $testDate,
            'test_date_year'   => '2014',
            'test_date_month'  => '01',
            'test_date_day'    => '01',
            'site_id'          => 1,
            'reason_code'      => EmergencyReasonCode::OTHER,
            'reason_text'      => 'This is a reason for the contingency'
        ];
        if ($fieldName) {
            unset($dtoData[$fieldName]);
        }
        return $dtoData;
    }

    /** Data provider helper to reduce code noise */
    protected function dpForMissing($fieldName)
    {
        static $errorMessage
        = [
            'contingency_code' => EmergencyLogValidator::ERR_CODE_INVALID,
            'tested_by_whom'   => EmergencyLogValidator::ERR_TESTER_CODE_REQUIRED,
            'tester_code'      => EmergencyLogValidator::ERR_TESTER_INVALID,
            'test_date'        => EmergencyLogValidator::ERR_DATE_REQUIRED,
            'site_id'          => EmergencyLogValidator::ERR_SITE_REQUIRED,
            'reason_code'      => EmergencyLogValidator::ERR_REASON_INVALID,
            'reason_text'      => EmergencyLogValidator::ERR_REASON_LENGTH
        ];

        return [
            $fieldName,
            $errorMessage[$fieldName],
            $this->dtoMinus($fieldName)
        ];
    }
}
