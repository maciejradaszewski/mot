<?php

namespace OrganisationApiTest\Controller;

use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonApiTest\Transaction\TestTransactionExecutor;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use OrganisationApi\Controller\OrganisationPositionController;
use OrganisationApi\Service\NominateRoleService;
use OrganisationApi\Service\OrganisationPositionService;

/**
 * Class OrganisationPositionControllerTest
 *
 * @package OrganisationApiTest\Controller
 */
class OrganisationPositionControllerTest extends AbstractRestfulControllerTestCase
{
    private $organisationId = 1;
    private $nomineeId      = 1;
    private $roleId         = 1;
    private $organisationPositionServiceMock;
    private $nominateRoleServiceMock;

    protected function setUp()
    {
        $this->controller                      = new OrganisationPositionController();
        $this->organisationPositionServiceMock = $this->getOrganisationPositionServiceMock();
        $this->nominateRoleServiceMock         = $this->getNominateRoleServiceMock();
        $this->setupServiceManager();
        TestTransactionExecutor::inject($this->controller);
        parent::setUp();
    }

    public function testGetListWithOrganisationIdCanBeAccessed()
    {
        //given
        $this->routeMatch->setParam('organisationId', $this->organisationId);

        //when
        $result = $this->controller->get($this->organisationId);

        //then
        $this->assertInstanceOf("Zend\View\Model\JsonModel", $result);
    }

    public function testCreateWithValidDataCanBeAccessed()
    {

        //given
        $this->routeMatch->setParam('organisationId', $this->organisationId);

        //when
        $result = $this->controller->create(
            [
                'nomineeId' => $this->nomineeId,
                'roleId'    => $this->roleId,
            ]
        );

        //then
        $this->assertInstanceOf("Zend\View\Model\JsonModel", $result);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\RequiredFieldException
     */
    public function testCreateWithMissingFieldsThrowsError()
    {
        //given
        $this->routeMatch->setParam('organisationId', $this->organisationId);

        //when
        $this->controller->create([]);
    }

    private function getOrganisationPositionServiceMock()
    {
        $organisationPositionServiceMock = XMock::of(OrganisationPositionService::class);

        $organisationPositionServiceMock->expects($this->any())
            ->method('getListForOrganisation')
            ->with($this->organisationId)
            ->will($this->returnValue([]));

        return $organisationPositionServiceMock;
    }

    private function getNominateRoleServiceMock()
    {
        $orgPosition              = new OrganisationBusinessRoleMap();
        $nominatedRoleServiceMock = XMock::of(NominateRoleService::class);

        $nominatedRoleServiceMock->expects($this->any())
            ->method('nominateRole')
            ->with($this->organisationId, $this->nomineeId, $this->roleId)
            ->will($this->returnValue($orgPosition));

        return $nominatedRoleServiceMock;
    }

    private function setupServiceManager()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(OrganisationPositionService::class, $this->organisationPositionServiceMock);
        $serviceManager->setService(NominateRoleService::class, $this->nominateRoleServiceMock);

        $this->controller->setServiceLocator($serviceManager);
    }
}
