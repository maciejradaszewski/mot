<?php
namespace DvsaMotApiTest\Controller;

use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Controller\MotCertificatesController;
use DvsaMotApi\Service\MotTestCertificatesService;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

class MotCertificatesControllerTest extends \PHPUnit_Framework_TestCase
{

    private $service;

    public function setUp()
    {
        $this->service = XMock::of(MotTestCertificatesService::class);
    }

    public function testGet_givenRequestReceived_shouldCallTheService()
    {
        $id = 5;
        $this->service->expects($this->once())->method("getCertificateDetails")
            ->with($id)->willReturn([]);

        $this->createController()->get($id);
    }


    public function testGetList_givenRequestReceivedWithVtsId_shouldCallTheService()
    {
        $vtsId = "5";

        $this->service->expects($this->once())->method("getCertificatesByVtsId")
            ->with($vtsId)->willReturn([]);

        $controller = $this->createController();
        $controller->getRequest()->setQuery(new Parameters(['vtsId' => $vtsId]));

        $controller->getList();
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testGetList_givenRequestReceivedWithoutVtsId_shouldNotCallTheService()
    {

        $this->service->expects($this->never())->method("getCertificatesByVtsId");
        $this->createController()->getList();
    }


    /**
     * @return MotCertificatesController
     */
    public function createController()
    {
        return new MotCertificatesController($this->service);
    }
}
