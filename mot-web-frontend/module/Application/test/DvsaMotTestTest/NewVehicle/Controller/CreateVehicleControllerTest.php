<?php
namespace DvsaMotTestTest\Controller;

use Core\Service\MotFrontendIdentityProviderInterface;
use CoreTest\Service\StubCatalogService;
use CoreTest\Service\StubRestForCatalog;
use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;
use Dvsa\Mot\Frontend\AuthenticationModule\Model\VehicleTestingStation;
use DvsaCommon\UrlBuilder\MotTestUrlBuilderWeb;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\HttpRestJson\Client;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\AbstractStep;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\CreateVehicleFormWizard;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\NewVehicle\Controller\CreateVehicleController;
use DvsaMotTest\NewVehicle\Form\CreateVehicleStepOneForm;
use DvsaMotTest\NewVehicle\Form\CreateVehicleStepTwoForm;
use DvsaMotTest\Service\AuthorisedClassesService;
use DvsaMotTest\NewVehicle\Container\NewVehicleContainer;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\SummaryStep;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\VehicleIdentificationStep;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\VehicleSpecificationStep;
use CoreTest\Controller\AbstractLightWebControllerTest;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Session\Container;
use Zend\Stdlib\ArrayObject;
use Zend\Stdlib\Parameters;
use Core\Service\RemoteAddress;
use Application\Service\ContingencySessionManager;

/* @method CreateVehicleController sut() service/controller under test, see parent::sut() */
class CreateVehicleControllerTest extends AbstractLightWebControllerTest
{
    const MAKE_ID = 'BMW';
    const MODEL_ID = 'Mini';
    const PERSON_ID = 1;
    const VTS_ID = 1;
    /** @var  Request */
    private $request;

    private $client;

    /** @var  NewVehicleContainer */
    private $container;

    /** @var MotFrontendIdentityProviderInterface */
    private $identityProvider;

    /** @var CreateVehicleFormWizard */
    private $wizard;

    /** @var ContingencySessionManager */
    private $contingencySessionManager;

    protected function setUp()
    {
        parent::setUp();
        $this->client = XMock::of(Client::class);
        $this->request = new Request();
        $this->container = new NewVehicleContainer(new ArrayObject());
        $this->identityProvider = XMock::of(MotFrontendIdentityProviderInterface::class);
        $this->contingencySessionManager = XMock::of(ContingencySessionManager::class);

        $this->expectIdentitySet();

        $this->wizard = $this->createWizard();
        $ctrl = new CreateVehicleController(
            $this->wizard,
            $this->authorisationMock,
            $this->request,
            $this->contingencySessionManager
        );
        $this->setController($ctrl);

        $this->authorisationMock->granted(PermissionInSystem::VEHICLE_CREATE);
    }

    private function expectIdentitySet()
    {
        $this->identityProvider->expects($this->any())->method('getIdentity')->willReturn(
            (new Identity())
                ->setCurrentVts((new VehicleTestingStation())->setVtsId(self::VTS_ID))
                ->setUserId(self::PERSON_ID)
        );
    }

    private function createWizard()
    {
        $authorisedClassesService = $this->getMock(AuthorisedClassesService::class, [], [$this->client]);
        $authorisedClassesService->expects($this->any())
            ->method('getCombinedAuthorisedClassesForPersonAndVts')
            ->willReturn($this->getCombinedAuthorisedClasses());

        $catalogService = new StubCatalogService();
        $wizard = new CreateVehicleFormWizard();

        $step1 = new VehicleIdentificationStep($this->container,$this->client,$catalogService);
        $wizard->addStep($step1);

        $step2 = new VehicleSpecificationStep($this->container,$this->client,$catalogService,$authorisedClassesService,$this->identityProvider);
        $step2->setPrevStep($step1);
        $wizard->addStep($step2);

        $step3 = new SummaryStep($this->container,$this->client,$catalogService, $this->identityProvider,$this->contingencySessionManager);
        $step3->setPrevStep($step2);
        $wizard->addStep($step3);

        return $wizard;
    }

    public function testIndex_givenEnteredIndex_shouldRedirect()
    {
        $query = ['reg' => 'RERG123'];
        $this->request->setQuery(new Parameters($query));
        $this->expectRedirect(
            CreateVehicleController::ROUTE,
            ['action' => 'add-step-one'],
            ['query' => $query]
        );
        $this->sut()->indexAction();


    }

    public function testAddStepOne_givenEnteredFirstStep_shouldReturnForm()
    {
        $this->request->setMethod('get');
        $vm = $this->sut()->addStepOneAction();
        $this->assertInstanceOf(CreateVehicleStepOneForm::class, $vm->getVariables()['form']);
    }

    public function testAddStepOne_givenSubmittedFirstStep_shouldRedirect()
    {
        $this->request->setMethod('post')->setPost(new Parameters($this->firstStepVehicleForm()));
        $this->expectRedirect(
            CreateVehicleController::ROUTE,
            ['action' => 'add-step-two']
        );
        $this->sut()->addStepOneAction();
    }

    public function testConfirm_givenEnteredConfirmation_shouldReturnCorrectViewModel()
    {
        $this->request->setMethod('get');
        $this->setDefaultDataToWizard();

        $this->client
            ->expects($this->any())
            ->method("get")
            ->willReturn($this->models());

        $vm = $this->sut()->confirmAction();

        $this->assertNotNull($vm->getVariables()['sectionOneData']);
        $this->assertNotNull($vm->getVariables()['sectionTwoData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testConfirm_givenSubmittedConfirmation_shouldRedirect($isMotContingency)
    {
        $motTestNumber = '121212';
        $expectedData = [
            'data'=> [
                'startedMotTestNumber' => $motTestNumber,
                'colour' => 'B',
                'countryOfRegistration' => '2',
                'cylinderCapacity' => '232',
                'fuelType' => 'PE',
                'make' => 'BMW',
                'model' => 'Mini',
                'modelType' => '',
                'registrationNumber' => 'reg1234',
                'secondaryColour' => 'W',
                'testClass' => '1',
                'transmissionType' => '2',
                'vin' => '1234567',
                'dateOfFirstUse' => '1999-12-12',
                'makeOther' => '',
                'modelOther' => '',
                'emptyVrmReason' => '',
                'emptyVinReason' => '',
                'vtsId' => 1,
                'clientIp' => RemoteAddress::getIp(),
            ]
        ];

        $this->request->setMethod('post');
        $this->setDefaultDataToWizard();

        $this->client->expects($this->any())->method('postJson')
            ->willReturn($expectedData);

        $this
            ->contingencySessionManager
            ->expects($this->any())
            ->method("isMotContingency")
            ->willReturn($isMotContingency);

        if ($isMotContingency) {
            $route = "mot-test";
        } else {
            $route = "mot-test/options";
        }

        $this->expectRedirect($route, ["motTestNumber" => $motTestNumber]);

        $this->sut()->confirmAction();
    }

    public function dataProvider()
    {
        return [[false], [true]];
    }

    public function testAddStepTwo_givenEnteredTheStep_shouldReturnForm()
    {
        $this->request->setMethod('get');
        $this->setStepOneData();
        $this->setApiData();

        $this->client->expects($this->any())->method('get')->willReturn($this->models());

        $vm = $this->sut()->addStepTwoAction();
        $this->assertInstanceOf(CreateVehicleStepTwoForm::class, $vm->getVariables()['form']);
        $this->assertNotEmpty($vm->getVariables('ccRequiredFuelType'));
    }

    public function testAddStepTwo_givenSubmittedSecondStep_shouldRedirect()
    {
        $this->request->setMethod('post');
        $postData = [];
        $postData += $this->secondStepVehicleForm();
        $this->request->setPost(new Parameters($postData));
        $this->setStepOneData();
        $this->setApiData();

        $this->client->expects($this->any())->method("get")->willReturn($this->models());

        $this->expectRedirect(CreateVehicleController::ROUTE, ['action' => 'confirm']);
        $this->sut()->addStepTwoAction();
    }

    private function models()
    {
        return ['data' => [['id' => 1, 'code' => 'Mini', 'name' => self::MODEL_ID]]];
    }

    private function getCombinedAuthorisedClasses()
    {
        return [
            AuthorisedClassesService::KEY_FOR_PERSON_APPROVED_CLASSES => [1, 2, 3, 4],
            AuthorisedClassesService::KEY_FOR_VTS_APPROVED_CLASSES => [1, 2, 3]
        ];
    }

    private function dataCatalog()
    {
        $data = (new StubRestForCatalog())->get();
        $data['model'] = [['code' => 'Mini', 'name' => 'Mini']];
        $data['fuelType'] = [['id' => 'PE', 'name' => 'Petrol']];
        $data['make'] = [['code' => self::MAKE_ID, 'name' => self::MAKE_ID]];
        $data['vehicleClass'] = [['id' => '1', 'name' => '1']];
        $data['emptyVrmReasons'] = null;
        $data['emptyVinReasons'] = null;
        $data['countryOfRegistration'] = [['id' => '2', 'name' => 'Country']];
        $data['transmissionType'] = [['id' => '2', 'name' => 'Automatic']];
        $data['colour'] = [['id' => 'B', 'name' => 'Black']];
        $data['secondaryColour'] = [['id' => 'W', 'name' => 'White']];

        return $data;
    }

    private function firstStepVehicleForm()
    {
        return [
            'vehicleForm' => [
                'make' => self::MAKE_ID,
                'VIN' => '1234567',
                'registrationNumber' => 'reg1234',
                'dateOfFirstUse' => ['year' => '1999', 'month' => '12', 'day' => '12'],
                'countryOfRegistration' => 2,
                'transmissionType' => '2',
                'emptyVrmReason' => null,
                'emptyVinReason' => null,
            ]
        ];
    }

    private function secondStepVehicleForm()
    {
        return [
            'vehicleForm' => [
                'model' => self::MODEL_ID,
                'fuelType' => 'PE',
                'vehicleClass' => '1',
                'cylinderCapacity' => '232',
                'modelDetail' => null,
                'colour' => 'B',
                'secondaryColour' => 'W',
            ]
        ];
    }

    /**
     * @return CreateVehicleStepOneForm
     */
    private function createStepOneForm()
    {
        $form = $this->getMockFormForStep(CreateVehicleStepOneForm::class);

        $form
            ->expects($this->any())
            ->method("getData")
            ->willReturn($this->firstStepVehicleForm());

        return $form;
    }

    /**
     * @return CreateVehicleStepTwoForm
     */
    private function createStepTwoForm()
    {
        $form = $this->getMockFormForStep(CreateVehicleStepTwoForm::class);

        $form
            ->expects($this->any())
            ->method("getData")
            ->willReturn($this->secondStepVehicleForm());

        return $form;
    }

    /**
     * @param string $formClass form's class path
     * @return \PHPUnit_Framework_MockObject_MockObject of CreateVehicleStepOneForm|CreateVehicleStepTwoForm
     * @throws \Exception
     */
    private function getMockFormForStep($formClass)
    {
        $form = XMock::of($formClass);
        $form
            ->expects($this->any())
            ->method("hasValidated")
            ->willReturn(true);

        return $form;
    }

    private function setDefaultDataToWizard()
    {
        $this->setStepOneData();
        $this->setStepTwoData();
        $this->setApiData();
    }

    private function setStepOneData()
    {
        $this->wizard->getStep(VehicleIdentificationStep::getName())->saveForm($this->createStepOneForm());
    }

    private function setStepTwoData()
    {
        $this->wizard->getStep(VehicleSpecificationStep::getName())->saveForm($this->createStepTwoForm());
    }

    private function setApiData()
    {
        $this->container->set(AbstractStep::API_DATA, $this->dataCatalog());
    }
}
