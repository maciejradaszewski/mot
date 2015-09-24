<?php
namespace SiteApiTest\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\MotTestRepository;
use SiteApi\Service\MotTestInProgressService;
use Zend\Authentication\AuthenticationService;

/**
 * Class MotTestInProgressServiceTest
 *
 * @package SiteApiTest\Service
 */
class MotTestInProgressServiceTest extends AbstractServiceTestCase
{
    /** @var MotTestRepository */
    private $motTestRepository;

    /** @var MotTestInProgressService */
    private $motTestInProgressService;

    public function setUp()
    {
        $this->setUpRepository();
        /** @var AuthorisationServiceInterface $authorizationService */
        $authorizationService = $this->getMockAuthorizationService();
        $this->motTestInProgressService = new MotTestInProgressService($this->motTestRepository, $authorizationService);
    }

    public function testGetTestInProgressReturnsDto()
    {
        // This code doesn't assert anything, it checks if code compiles.
        $dtoArray = $this->motTestInProgressService->getAllForSite(1);

        $this->assertTrue(is_array($dtoArray));
        $motTestDto = $dtoArray[0];

        $this->assertEquals("1001", $motTestDto->getNumber());
        $this->assertEquals("John Johnson", $motTestDto->getTesterName());
        $this->assertEquals("LAMP101", $motTestDto->getVehicleRegisteredNumber());
        $this->assertEquals("Clio", $motTestDto->getVehicleModel());
        $this->assertEquals("Renault", $motTestDto->getVehicleMake());
    }

    public function setUpRepository()
    {
        $tester = new Person();
        $tester->setFirstName("John");
        $tester->setFamilyName("Johnson");

        $vehicleMake = new Make();
        $vehicleMake->setId(1);
        $vehicleMake->setCode("Renau");
        $vehicleMake->setName("Renault");

        $vehicleModel = new Model();
        $vehicleModel->setId(2);
        $vehicleModel->setCode("CLIO");
        $vehicleModel->setName("Clio");

        $motTest = new MotTest();
        $motTest->setTester($tester);

        $motTest->setMake($vehicleMake);
        $motTest->setModel($vehicleModel);

        $motTest->setNumber(1001);
        $motTest->setRegistration("LAMP101");

        $this->motTestRepository = $this->getMockWithDisabledConstructor(MotTestRepository::class);
        $this->motTestRepository->expects($this->any())->method('findInProgressTestsForVts')->will(
            $this->returnValue([$motTest])
        );
    }
}
