<?php

namespace VehicleApiTest\Controller;

use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;
use DvsaMotApiTest\Controller\AbstractMotApiControllerTestCase;
use VehicleApi\Controller\VehicleRetestEligibilityController;

/**
 * Class VehicleRetestEligibilityController
 *
 * @package DvsaMotApiTest\Controller
 */
class VehicleRetestEligibilityControllerTest extends AbstractMotApiControllerTestCase
{
    protected function setUp()
    {
        $this->controller = new VehicleRetestEligibilityController();
        parent::setUp();
    }

    public function testCreateEligibilityWithValidData()
    {
        $person = new Person();
        $person->setId(5);

        $this->mockValidAuthorization([SiteBusinessRoleCode::TESTER, 'TESTER-CLASS-4'], null, $person);

        $mockMotTestService = $this->getMockService();
        $mockMotTestService->expects($this->once())
            ->method('checkEligibilityForRetest')
            ->willReturn(true);

        $result = $this->getResultForAction('post', null, ['id' => 3, 'siteId' => 1]);

        $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, ['data' => ['isEligible' => true]], $result);
    }

    public function testCreateEligibilityWithValidDataAndContingency()
    {
        $person = new Person();
        $person->setId(5);

        $this->mockValidAuthorization([SiteBusinessRoleCode::TESTER, 'TESTER-CLASS-4'], null, $person);

        $mockMotTestService = $this->getMockService();
        $mockMotTestService->expects($this->once())
            ->method('checkEligibilityForRetest')
            ->willReturn(true);

        $this->request->getPost()->set(
            'contingencyDto', [
                "_class" => "DvsaCommon\\Dto\\MotTesting\\ContingencyMotTestDto"
            ]
        );
        $result = $this->getResultForAction('post', null, ['id' => 3, 'siteId' => 1]);

        $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, ['data' => ['isEligible' => true]], $result);
    }

    private function getMockService()
    {
        $mockService = XMock::of(RetestEligibilityValidator::class, ['checkEligibilityForRetest']);

        $this->serviceManager->setService('RetestEligibilityValidator', $mockService);

        return $mockService;
    }
}
