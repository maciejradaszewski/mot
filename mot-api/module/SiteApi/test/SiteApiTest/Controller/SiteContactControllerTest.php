<?php

namespace SiteApiTest\Controller;

use DvsaCommon\Constants\Role;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use SiteApi\Controller\SiteContactController;
use SiteApi\Service\SiteContactService;
use Zend\Http\Header\ContentType;

/**
 * Class SiteContactControllerTest
 *
 * @package SiteApiTest\Controller
 */
class SiteContactControllerTest extends AbstractRestfulControllerTestCase
{
    const SITE_ID = 1;
    const SITE_NR = 'V1234';
    const CONTACT_ID = 99999;

    protected function setUp()
    {
        $this->setController(new SiteContactController());
        parent::setUp();

        $this->mockValidAuthorization([Role::VEHICLE_EXAMINER]);
    }

    /**
     * Test method is accessible for call with valid parameters
     *
     * @param string $method       HTTP method
     * @param string $action       route action
     * @param array  $params       route parameters
     * @param string $srvMethod    mocked service method
     * @param array  $srvReturn    service method will return
     * @param array  $expectResult expected method result
     *
     * @dataProvider dataProviderTestWithValidParam
     */
    public function testWithValidParam(
        $method,
        $action,
        $params,
        $srvMethod,
        $srvReturn,
        $expectResult
    ) {
        $mockSiteContactSrv = $this->getMockSiteContactService();
        $this->mockMethod($mockSiteContactSrv, $srvMethod, $this->once(), $srvReturn);

        $result = $this->getResultForAction($method, $action, $params);

        $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, $expectResult, $result);
    }


    public function dataProviderTestWithValidParam()
    {
        $contactDto = new SiteContactDto;
        $contactDto->setId(self::CONTACT_ID);

        $jsonContactDto = DtoHydrator::dtoToJson($contactDto);

        $postSrvResult = ['id' => self::CONTACT_ID];
        $postExpect = ['data' => $postSrvResult];

        return [
            [
                'method'        => 'put',
                'action'        => null,
                'params'        => ['id' => self::SITE_ID, 'data' => $jsonContactDto],
                'serviceMethod' => 'updateContactFromDto',
                'serviceReturn' => $postSrvResult,
                'expectResult'  => $postExpect,
            ],
        ];
    }



    /**
     * Test service method return errors when call with invalid parameters
     *
     * @param string $method        HTTP method
     * @param string $action        route action
     * @param array  $params        route params
     * @param string $srvMethod     mocked service method
     * @param array  $srvMethodParams mocked service method params
     * @param string $exceptionKey key for exception
     *
     * @dataProvider dataProviderTestWithInvalidParams
     */
    public function testWithInvalidParams(
        $method,
        $action,
        $params,
        $srvMethod,
        $srvMethodParams,
        $exceptionKey
    ) {
        $mockSiteContactSrv = $this->getMockSiteContactService();
        $exception = new NotFoundException('SiteContact', $exceptionKey);
        $this->mockMethod($mockSiteContactSrv, $srvMethod, $this->once(), $exception, $srvMethodParams);

        $this->setExpectedException(
            NotFoundException::class,
            sprintf(NotFoundException::ERROR_MSG_NOT_FOUND, 'SiteContact', $exceptionKey),
            NotFoundException::ERROR_CODE_NOT_FOUND
        );

        $this->getResultForAction($method, $action, $params);
    }

    public function dataProviderTestWithInvalidParams()
    {
        $contactDto = new SiteContactDto;
        $jsonContactDto = DtoHydrator::dtoToJson($contactDto);

        return [
            [
                'method'          => 'put',
                'action'          => null,
                'params'          => ['id' => 9999, 'data' => $jsonContactDto],
                'srvMethod'       => 'updateContactFromDto',
                'srvMethodParams' => ['id' => 9999, 'data' => $contactDto],
                '$exceptionKey'   => null,
            ],
        ];
    }


    /**
     * Test service method return errors when required parameter is null
     *
     * @param string $method      HTTP method
     * @param string $action      route action
     * @param string $paramName   set value for parameter with name
     * @param string $expectError error
     *
     * @dataProvider dataProviderTestParamNull
     */
    public function testParamNull($method, $action, $paramName, $expectError)
    {
        $result = $this->getResultForAction($method, $action, [$paramName => null]);

        $this->assertResultHasErrors($result, [$expectError]);
    }

    public function dataProviderTestParamNull()
    {
        $errSiteId = [
            'message' => SiteContactController::SITE_ID_REQUIRED_MESSAGE,
            'code'    => SiteContactController::ERROR_CODE_REQUIRED,
        ];

        return [
            ['put', null, 'id', $errSiteId],
        ];
    }


    public function testGetService()
    {
        $this->assertEquals(
            $this->getMockSiteContactService(),
            XMock::invokeMethod($this->getController(), 'getSiteContactService')
        );
    }

    /**
     * @return SiteContactService|MockObj
     */
    private function getMockSiteContactService()
    {
        return $this->getMockServiceManagerClass(SiteContactService::class, SiteContactService::class);
    }
}
