<?php
namespace DvsaMotTestTest\Controller;

use Application\Helper\PrgHelper;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use Dvsa\Mot\Frontend\Test\StubIdentityAdapter;
use DvsaCommon\Auth\MotIdentityProvider;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Constants\OdometerReadingResultType;
use DvsaCommon\Constants\OdometerUnit;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Common\MotTestTypeDto;
use DvsaCommon\Dto\Common\OdometerReadingDTO;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\HttpRestJson\Exception\OtpApplicationException;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\MotTestUrlBuilder;
use DvsaCommon\UrlBuilder\MotTestUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\Controller\MotTestController;
use DvsaMotTest\Service\SurveyService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\Plugin\Forward;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class MotTestControllerTest.
 */
class MotTestControllerTest extends AbstractDvsaMotTestTestCase
{
    /**
     * @var MotFrontendAuthorisationServiceInterface
     */
    private $authServiceMock;

    /**
     * @var SurveyService
     */
    private $surveyServiceMock;

    protected function setUp()
    {
        $this->authServiceMock = XMock::of(MotFrontendAuthorisationServiceInterface::class);
        $this->surveyServiceMock = $this->getMockBuilder(SurveyService::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->controller = new MotTestController($this->authServiceMock, $this->surveyServiceMock);

        $serviceManager = Bootstrap::getServiceManager();
        $this->controller->setServiceLocator($serviceManager);

        parent::setUp();

        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
    }

    public function testRequestIsForwardedToMotTestResultsControllerIfFeatureToggleIsEnabled()
    {
        $this->withFeatureToggles([FeatureToggle::TEST_RESULT_ENTRY_IMPROVEMENTS => true]);

        $forwardPlugin = $this
            ->getMockBuilder(Forward::class)
            ->disableOriginalConstructor()
            ->getMock();
        $forwardPlugin
            ->expects($this->once())
            ->method('dispatch')
            ->will($this->returnValue(new ViewModel()));

        $this->getController()->getPluginManager()->setService('forward', $forwardPlugin);

        $result = $this->getResultForAction('index', ['motTestNumber' => 656402615654]);
    }

    public function testMotTestIndexCanBeAccessedForAuthenticatedRequest()
    {
        $motTestNr = (int) rand(1e12, 1e13 - 1);
        $motTestData = $this->getTestMotTestDataDto($motTestNr);

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->at(0))
            ->method('get')
            ->with(UrlBuilder::of()->motTest()->routeParam('motTestNumber', $motTestNr))
            ->willReturn(['data' => $motTestData]);

        $restClientMock->expects($this->at(1))
            ->method('get')
            ->with($this->anything())
            ->willReturn(['data' => []]);

        $result = $this->getResultForAction('index', ['motTestNumber' => $motTestNr]);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
        $this->assertEquals($motTestData, $result->motTest);
    }

    public function testMotTestIndexWithoutIdParameterFails()
    {
        $this->controller->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_ERR_404);
    }

    public function testMotTestUpdateOdometerPostWithValidData()
    {
        $motTestNumber = 1;
        $odometerValue = '100';
        $odometerUnit = OdometerUnit::MILES;
        $odometerResultType = OdometerReadingResultType::OK;

        $expectedRestPostData = [
            'value'      => $odometerValue,
            'unit'       => $odometerUnit,
            'resultType' => $odometerResultType,
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('put')
            ->with(MotTestUrlBuilder::odometerReading($motTestNumber)->toString(), $expectedRestPostData);

        //  --  request & check    --
        $postParams = [
            'odometer'   => $odometerValue,
            'unit'       => $odometerUnit,
            'resultType' => $odometerResultType,
        ];

        $this->getResultForAction2('post', 'updateOdometer', ['motTestNumber' => $motTestNumber], null, $postParams);

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::motTest($motTestNumber));
    }

    public function testMotTestUpdateOdometer_givenPostWithInvalidData_shouldFlashErrorAndRedirect()
    {
        $motTestNumber = 1;
        $odometerValue = '';
        $odometerUnit = OdometerUnit::MILES;
        $odometerResultType = OdometerReadingResultType::OK;

        $this->getFlashMessengerMockForAddErrorMessage('Odometer value must be entered to update odometer reading');

        //  --  request & check    --
        $postParams = [
            'odometer'   => $odometerValue,
            'unit'       => $odometerUnit,
            'resultType' => $odometerResultType,
        ];

        $this->getResultForAction2('post', 'updateOdometer', ['motTestNumber' => $motTestNumber], null, $postParams);

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::motTest($motTestNumber));
    }

    public function testMotTestUpdateOdometerPostWithValidDataNoOdometerValue()
    {
        $motTestNumber = 1;
        $odometerValue = '100';
        $odometerUnit = OdometerUnit::MILES;
        $odometerResultType = OdometerReadingResultType::NO_ODOMETER;

        $expectedRestPostData = [
            'resultType' => $odometerResultType,
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('put')
            ->with(MotTestUrlBuilder::odometerReading($motTestNumber)->toString(), $expectedRestPostData);

        //  --  request & check    --
        $postParams = [
            'odometer' => $odometerValue,
            'unit'     => $odometerUnit,
            'resultType' => $odometerResultType,
        ];

        $this->getResultForAction2('post', 'updateOdometer', ['motTestNumber' => $motTestNumber], null, $postParams);

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::motTest($motTestNumber));
    }

    public function testMotTestIndexPostWithInvalidOdometerLetters()
    {
        $this->getFlashMessengerMockForAddErrorMessage(MotTestController::ODOMETER_FORM_ERROR_MESSAGE);

        $postParams = [
            'odometer' => 'ABCD',
            'unit'     => 'km',
            'resultType' => OdometerReadingResultType::OK,
        ];
        $this->getResultForAction2('post', 'updateOdometer', null, null, $postParams);

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }

    public function testMotTestIndexPostWithInvalidOdometerWithDecimalPoint()
    {
        $this->getFlashMessengerMockForAddErrorMessage(MotTestController::ODOMETER_FORM_ERROR_MESSAGE);

        $postParams = [
            'odometer' => '12.44',
            'unit'     => 'km',
            'resultType' => OdometerReadingResultType::OK,
        ];
        $this->getResultForAction2('post', 'updateOdometer', null, null, $postParams);

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }

    public function testMotTestIndexPostWithInvalidOdometerWithMoreThanSixDigits()
    {
        $this->getFlashMessengerMockForAddErrorMessage(MotTestController::ODOMETER_FORM_ERROR_MESSAGE);

        $postParams = [
            'odometer' => '1234567',
            'unit'     => 'km',
            'resultType' => OdometerReadingResultType::OK,
        ];
        $this->getResultForAction2('post', 'updateOdometer', null, null, $postParams);

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }

    public function testMotTestIndexPostWithInvalidOdometerWithComma()
    {
        $this->getFlashMessengerMockForAddErrorMessage(MotTestController::ODOMETER_FORM_ERROR_MESSAGE);

        $postParams = [
            'odometer' => '12,44',
            'unit'     => 'km',
            'resultType' => OdometerReadingResultType::OK,
        ];
        $this->getResultForAction2('post', 'updateOdometer', null, null, $postParams);

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }

    public function testMotTestIndexPostWithInvalidOdometerWithWhiteSpaceOnly()
    {
        $this->getFlashMessengerMockForAddErrorMessage(MotTestController::ODOMETER_VALUE_REQUIRED_MESSAGE);

        $postParams = [
            'odometer' => '  ',
            'unit'     => 'km',
            'resultType' => OdometerReadingResultType::OK,
        ];
        $this->getResultForAction2('post', 'updateOdometer', null, null, $postParams);

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }

    public function testSubmitTestResultsWithStatus()
    {
        $authServiceMock = XMock::of(MotFrontendAuthorisationServiceInterface::class);
        $serviceManager = $this->getServiceManager();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('AuthorisationService', $authServiceMock);
        $authServiceMock->expects($this->once())
            ->method('isVehicleExaminer')
            ->willReturn(true);

        $motTestNumber = 9999;
        $status = 'testStatus';
        $siteid = 1;

        //  --  request & check    --
        $postParams = [
            'status' => $status,
            'siteid' => $siteid,
            'onePersonTest' => 1,
            'onePersonReInspection' => 1,
        ];

        $this->getResultForAction2(
            'post', 'displayTestSummary', ['motTestNumber' => $motTestNumber], null, $postParams
        );

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::showResult($motTestNumber));
    }

    public function testSubmitTestResultsCatchOtpApplicationException()
    {
        $this->setupAuthorizationService(
            [PermissionInSystem::MOT_TEST_CONFIRM, PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE]
        );

        $motTestNumber = 9999;
        $status = 'testStatus';
        $siteid = 1;

        //  --  request & check    --
        $postParams = [
            'status' => $status,
            'siteid' => $siteid,
            'onePersonTest' => 1,
            'onePersonReInspection' => 1,
            'clientIp' => '0.0.0.0',
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('post')
            ->with(MotTestUrlBuilder::motTestStatus($motTestNumber), $postParams)
            ->willThrowException(
                new OtpApplicationException(
                    '/',
                    'post',
                    [],
                    10,
                    [],
                    ['message' => 'error', 'shortMessage' => 'error']
                )
            );
        $restClientMock->expects($this->once())
            ->method('get')
            ->with(MotTestUrlBuilder::motTest($motTestNumber))
            ->willReturn(['data' => $this->getTestMotTestDataDto($motTestNumber)]);

        $this->getResultForAction2(
            'post', 'displayTestSummary', ['motTestNumber' => $motTestNumber], null, $postParams
        );

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testSubmitTestResultsCatchRestApplicationException()
    {
        $this->setupAuthorizationService(
            [PermissionInSystem::MOT_TEST_CONFIRM, PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE]
        );

        $motTestNumber = 9999;
        $status = 'testStatus';
        $siteid = 1;

        //  --  request & check    --
        $postParams = [
            'status' => $status,
            'siteid' => $siteid,
            'onePersonTest' => 1,
            'onePersonReInspection' => 1,
            'clientIp' => '0.0.0.0',
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('post')
            ->with(MotTestUrlBuilder::motTestStatus($motTestNumber), $postParams)
            ->willThrowException(new RestApplicationException('/', 'post', [], 10, [['displayMessage' => 'error']]));
        $restClientMock->expects($this->once())
            ->method('get')
            ->with(MotTestUrlBuilder::motTest($motTestNumber))
            ->willReturn(['data' => $this->getTestMotTestDataDto($motTestNumber)]);

        $this->getResultForAction2(
            'post', 'displayTestSummary', ['motTestNumber' => $motTestNumber], null, $postParams
        );

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testSubmitTestResultsCatchRestApplicationExceptionRedirect()
    {
        $this->setupAuthorizationService(
            [PermissionInSystem::MOT_TEST_CONFIRM, PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE]
        );

        $motTestNumber = 9999;
        $status = 'testStatus';
        $siteid = 1;

        //  --  request & check    --
        $postParams = [
            'status' => $status,
            'siteid' => $siteid,
            'onePersonTest' => 1,
            'onePersonReInspection' => 1,
            'clientIp' => '0.0.0.0',
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('post')
            ->with(MotTestUrlBuilder::motTestStatus($motTestNumber), $postParams)
            ->willThrowException(
                new RestApplicationException(
                    '/',
                    'post',
                    [],
                    10,
                    [['displayMessage' => 'This test has been aborted by DVSA and cannot be continued']]
                )
            );

        $this->getResultForAction2(
            'post', 'displayTestSummary', ['motTestNumber' => $motTestNumber], null, $postParams
        );

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::showResult($motTestNumber));
    }

    public function testDisplayTestResultsWithStatus()
    {
        $this->setupAuthorizationService(
            [PermissionInSystem::MOT_TEST_CONFIRM, PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE]
        );

        $motTestNumber = 9999;
        $status = 'testStatus';
        $siteid = 1;

        //  --  request & check    --
        $postParams = [
            'status' => $status,
            'siteid' => $siteid,
            'onePersonTest' => 1,
            'onePersonReInspection' => 1,
            'clientIp' => '0.0.0.0',
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('get')
            ->with(MotTestUrlBuilder::motTest($motTestNumber))
            ->willReturn(['data' => $this->getTestMotTestDataDto($motTestNumber)]);

        $this->getResultForAction2(
            'get', 'displayTestSummary', ['motTestNumber' => $motTestNumber], null, $postParams
        );

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testCancelMotTestWithValidPostDataOk()
    {
        $motTestNumber = 1;
        $status = 'CANCEL';
        $reasonForCancel = 1;

        $expectedRestPostData = [
            'status'          => $status,
            'reasonForCancel' => $reasonForCancel,
            'clientIp' => '0.0.0.0',
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('get')
            ->with(MotTestUrlBuilder::motTest($motTestNumber))
            ->willReturn(['data' => $this->getTestMotTestDataDto($motTestNumber)]);
        $restClientMock->expects($this->once())
            ->method('post')
            ->with(MotTestUrlBuilder::motTestStatus($motTestNumber), $expectedRestPostData)
            ->will($this->returnValue(['testType' => 'NT']));

        //  --  request & check    --
        $postParams = [
            'status'          => $status,
            'reasonForCancel' => $reasonForCancel,
        ];

        $this->getResultForAction2('post', 'cancelMotTest', ['motTestNumber' => $motTestNumber], null, $postParams);

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::cancelled($motTestNumber));
    }

    public function testAbandonMotTestWithValidPostDataOk()
    {
        $motTestNumber = 1;
        $status = 'CANCEL';
        $reasonForCancel = 1;
        $oneTimePassword = '123456';

        $expectedRestPostData = [
            'status'          => $status,
            'reasonForCancel' => $reasonForCancel,
            'oneTimePassword' => $oneTimePassword,
            'clientIp' => '0.0.0.0',
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('get')
            ->with(MotTestUrlBuilder::motTest($motTestNumber))
            ->willReturn(['data' => $this->getTestMotTestDataDto($motTestNumber)]);
        $restClientMock->expects($this->once())
            ->method('post')
            ->with(MotTestUrlBuilder::motTestStatus($motTestNumber), $expectedRestPostData)
            ->willReturn(['testType' => 'NT']);

        //  --  request & check    --
        $postParams = [
            'status'          => $status,
            'reasonForCancel' => $reasonForCancel,
            'oneTimePassword' => $oneTimePassword,
        ];

        $this->getResultForAction2('post', 'cancelMotTest', ['motTestNumber' => $motTestNumber], null, $postParams);

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::cancelled($motTestNumber));
    }

    public function testAbandonMotTestWithWrongOneTimePassword()
    {
        $motTestNumber = 1;
        $status = 'CANCEL';
        $reasonForCancel = 1;
        $oneTimePassword = '123456';

        $expectedRestPostData = [
            'status'            => $status,
            'reasonForCancelId' => $reasonForCancel,
            'oneTimePassword'   => $oneTimePassword,
            'clientIp' => '0.0.0.0',
        ];

        $restClientMock = $this->getRestClientMockForServiceManager();

        $restClientMock->expects($this->once())
            ->method('get')
            ->with(MotTestUrlBuilder::motTest($motTestNumber))
            ->willReturn(['data' => $this->getTestMotTestDataDto($motTestNumber)]);

        $restResourcePath = MotTestUrlBuilder::motTestStatus($motTestNumber);
        $restClientMock->expects($this->once())
            ->method('post')
            ->with($restResourcePath, $expectedRestPostData)
            ->willThrowException(new OtpApplicationException($restResourcePath, null, null, 403, [], []));

        //  --  request & check --
        $postParams = [
            'status'            => $status,
            'reasonForCancelId' => $reasonForCancel,
            'oneTimePassword'   => $oneTimePassword,
        ];

        $this->getResultForAction2('post', 'cancelMotTest', ['motTestNumber' => $motTestNumber], null, $postParams);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    /**
     * @dataProvider cancelledMotTestActionDataProvider
     */
    public function testCancelledMotTestCanBeAccessedAuthenticatedRequest($motTestStatus, $expectedTestDocument)
    {
        $motTestNr = (int) rand(1, 1000);

        $motTestData = $this->getTestMotTestDataDto($motTestNr, $motTestStatus);

        $this->setupAuthorizationService();

        $this->getRestClientMockWithGetMotTest(['data' => $motTestData]);

        $result = $this->getResultForAction('cancelledMotTest', ['motTestNumber' => $motTestNr]);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
        $this->assertEquals($motTestData, $result->motTest);
        $this->assertEquals($expectedTestDocument, $result->testDocument);
    }

    public static function cancelledMotTestActionDataProvider()
    {
        return [
            [MotTestStatusName::ABORTED, MotTestController::TEST_DOCUMENT_VT32],
            [MotTestStatusName::ABANDONED, MotTestController::TEST_DOCUMENT_VT30],
        ];
    }

    public function testDisplayTestSummaryCanBeAccessedAuthenticatedRequest()
    {
        $this->setupAuthorizationService(
            [PermissionInSystem::MOT_TEST_CONFIRM, PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE]
        );

        $motTestNr = (int) rand(1, 1000);
        $motTestData = $this->getTestMotTestDataDto($motTestNr);

        $this->getRestClientMockWithGetMotTest(['data' => $motTestData]);

        $result = $this->getResultForAction('displayTestSummary', ['motTestNumber' => $motTestNr]);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
        $this->assertEquals($motTestData, $result->motDetails);
    }

    public function testDisplayCertificateSummary()
    {
        $motTestNr = (int) rand(1, 1000);
        $motTestData = $this->getTestMotTestDataDto($motTestNr);

        $this->setupAuthorizationService([PermissionInSystem::CERTIFICATE_READ]);

        $this->getRestClientMock('get', ['data' => $motTestData], "mot-test-certificate?number=$motTestNr");

        $result = $this->getResultForAction('displayCertificateSummary', ['motTestNumber' => $motTestNr]);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
        $this->assertEquals($motTestData, $result->motDetails);
    }

//    public function testDisplayTestSummaryWithRestClientThrowingException() TODO - JK
//    {
//        $motTestNumber = 1;
//        $errorMessage = "Test error message from REST";
//
//        $this->routeMatch->setParam('action', 'displayTestSummary');
//        $this->routeMatch->setParam('motTestNumber', $motTestNumber);
//
//        $this->getRestClientMockThrowingException('get', $errorMessage);
//
//        $this->getFlashMessengerMockForAddManyErrorMessage($errorMessage);
//
//        $this->controller->dispatch($this->request);
//
//        $response = $this->controller->getResponse();
//        $this->assertEquals(200, $response->getStatusCode());
//    }

    /**
     * Post from summary page, mot test already finished (not active status), redirect to finish page by motTestNumber.
     */
    public function testDisplaySummaryPostActionGivenMotTestNotActiveShouldRedirectToFinish()
    {
        $this->setupAuthorizationService(
            [PermissionInSystem::MOT_TEST_CONFIRM, PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE]
        );

        $motTestNumber = 99999;

        $motTestDto = $this->getTestMotTestDataDto($motTestNumber);

        $restClient = $this->getRestClientMockForServiceManager();
        $this->mockMethod(
            $restClient, 'get', null,
            ['data' => $motTestDto],
            MotTestUrlBuilder::motTest($motTestNumber)->toString()
        );

        $this->getResultForAction2('post', 'displayTestSummary', ['motTestNumber' => $motTestNumber]);

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::showResult($motTestNumber));
    }

    /**
     * Check for double post.
     *
     * @dataProvider dataProviderTestDoublePost
     */
    public function testDoublePost($action)
    {
        $this->setupAuthorizationService(
            [PermissionInSystem::MOT_TEST_CONFIRM, PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE]
        );

        $tokenGuid = 'testToken';

        $session = new Container('prgHelperSession');
        $session->offsetSet($tokenGuid, 'redirectUrl');

        $postParams = [
            PrgHelper::FORM_GUID_FIELD_NAME => $tokenGuid,
        ];
        $this->getResultForAction2('post', 'displayTestSummary', null, null, $postParams);

        $this->assertRedirectLocation2('redirectUrl');
    }

    public function dataProviderTestDoublePost()
    {
        return [
            ['action' => 'displayTestSummary'],
            ['action' => 'cancelMotTest'],
        ];
    }

    public function testDeleteRfrActionDeletesOk()
    {
        $motTestNumber = 1;
        $rfrId = 123;

        $restClientMock = $this->getRestClientMockForServiceManager();
        $restClientMock->expects($this->once())
            ->method('delete')
            ->with(MotTestUrlBuilder::reasonForRejection($motTestNumber, $rfrId));

        //  --  request & check --
        $routeParams = [
            'motTestNumber' => $motTestNumber,
            'rfr-id'        => $rfrId,
        ];

        $this->getResultForAction2('delete', 'deleteReasonForRejection', $routeParams);

        $this->assertRedirectLocation2(MotTestUrlBuilderWeb::motTest($motTestNumber));
    }

    public function testPrintMotTestCanBeAccessedAuthenticatedRequest()
    {
        $motTestNr = (int) rand(1, 1000);
        $motTestData = $this->getTestMotTestDataDto($motTestNr);

        $this->setupAuthorizationService();
        $this->getRestClientMockWithGetMotTest(['data' => $motTestData]);

        $result = $this->getResultForAction('testResult', ['motTestNumber' => $motTestNr]);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
        $this->assertEquals($motTestData, $result->motDetails);
    }

    public function retrievePdfDuplicateDataProvider()
    {
        return [
            [true],
            [false],
        ];
    }

    private function getTestMotTestDataDto($motTestNumber = 1, $status = MotTestStatusName::PASSED)
    {
        /** @var MotIdentityProvider $mockIdentityProvider */
        $mockIdentityProvider = $this->getServiceManager()->get('MotIdentityProvider');

        $motTest = (new MotTestDto())
            ->setMotTestNumber($motTestNumber)
            ->setTester((new PersonDto())->setId($mockIdentityProvider->getIdentity()->getUserId()))
            ->setTestType((new MotTestTypeDto())->setCode(MotTestTypeCode::NORMAL_TEST))
            ->setStatus($status)
            ->setOdometerReading(
                (new OdometerReadingDTO())
                    ->setValue(1234)
                    ->setUnit(OdometerUnit::KILOMETERS)
                    ->setResultType(OdometerReadingResultType::OK)
            )
            ->setVehicle(
                (new VehicleDto())
                    ->setId(1)
                    ->setRegistration('ELFA 1111')
                    ->setVin('1M2GDM9AXKP042725')
                    ->setYear(2011)
                    ->setVehicleClass(
                        (new VehicleClassDto())
                            ->setId(4)
                            ->setCode(4)
                    )
                    ->setMakeName('Volvo')
                    ->setModelName('S80 GTX')
            );

        return $motTest;
    }

    private function getTestMotTestData(array $params = [])
    {
        $vehicleClass = 4;

        $motTest = $this->jsonFixture('mot-test', __DIR__);

        $result = array_replace_recursive(
            $motTest['data'], [
                'motTestNumber'   => 1,
                'tester'          => new PersonDto(),
                "testType"        => (new MotTestTypeDto())->setCode(MotTestTypeCode::NORMAL_TEST),
                "status"          => MotTestStatusName::PASSED,
                //                "reasons_for_rejection" => [
                //                    ['rfr-id' => 1], ['rfr-id' => 2]],
                //                "break_test_results"    => [['break-result-id' => 1]],
                "odometerReading" => [
                    'value'      => 1234,
                    'unit'       => OdometerUnit::KILOMETERS,
                    'resultType' => OdometerReadingResultType::OK,
                ],
                'vehicle'         => [
                    'id'           => 1,
                    'registration' => 'ELFA 1111',
                    'vin'          => '1M2GDM9AXKP042725',
                    'year'         => 2011,
                    'modelDetail'  => [
                        'make'  => [
                            'name' => 'Volvo',
                        ],
                        'model' => [
                            'name' => 'S80 GTX',
                        ],
                    ],
                    'fuel_type'    => [
                        'id' => 'X',
                    ],
                    //                    'firstUsedDate' => '2001-02-02',
                    'colour'       => [
                        'id'   => 'B',
                        'name' => 'Black',
                    ],
                    'vehicleClass' => [
                        'id'   => $vehicleClass,
                        'code' => $vehicleClass,
                    ],
                ],
            ],
            $params
        );

        return ['data' => $result];
    }

    private function getRestClientMockWithGetMotTest($motTestData)
    {
        $motTestNumber = is_object($motTestData['data'])
            ? $motTestData['data']->getMotTestNumber()
            : $motTestData['data']['motTestNumber'];

        return $this->getRestClientMock(
            'get',
            $motTestData,
            "mot-test/$motTestNumber"
        );
    }

    private function motTestToDto($motTestData)
    {
        $result = $motTestData['data'];
        $result['vehicle'] = DtoHydrator::jsonToDto($result['vehicle']);

        return $result;
    }

    public static function TestIpExtractionFromHeaderDataProvider()
    {
        return [
            ["1.1.1.1", "1.1.1.1"],
            ["1.1.1.1, 2.2.2.2, 3.3.3.3", "1.1.1.1"],
            ["", "0.0.0.0"],
            [",", "0.0.0.0"],
        ];
    }

    /**
     * @dataProvider TestIpExtractionFromHeaderDataProvider
     */
    public function testCorrectlyObtainFirstIpFromHeader($testHeader, $expectedIp)
    {
        $this->setupAuthorizationService(
            [PermissionInSystem::MOT_TEST_CONFIRM, PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE]
        );

        $this->request->getHeaders()->addHeaders([
            'X-Forwarded-For' => $testHeader,
        ]);

        $motTestNr = (int) rand(1, 1000);
        $motTestData = $this->getTestMotTestDataDto($motTestNr);
        $expectedPostData = ['clientIp' => $expectedIp];

        $restClientMock = $this->getRestClientMockWithGetMotTest(['data' => $motTestData]);
        $this->mockMethod($restClientMock, 'post', $this->once(), null, ["mot-test/$motTestNr/status", $expectedPostData]);
        $this->getResultForAction2('post', 'displayTestSummary', ['motTestNumber' => $motTestNr]);

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }
}
