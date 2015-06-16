<?php
namespace DvsaMotTestTest\Controller;

use Core\Service\MotFrontendIdentityProviderInterface;
use CoreTest\Service\StubCatalogService;
use CoreTest\Service\StubRestForCatalog;
use DvsaAuthentication\Model\Identity;
use DvsaAuthentication\Model\VehicleTestingStation;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\UrlBuilder\VehicleUrlBuilder;
use DvsaCommonTest\TestUtils\MultiCallStubBuilder;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\NewVehicle\Controller\CreateVehicleController;
use DvsaMotTest\NewVehicle\Form\CreateVehicleStepOneForm;
use DvsaMotTest\NewVehicle\Form\CreateVehicleStepTwoForm;
use DvsaMotTest\Service\AuthorisedClassesService;
use DvsaMotTest\NewVehicle\Container\NewVehicleContainer;
use UserAdminTest\Controller\AbstractLightWebControllerTest;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Zend\Stdlib\ArrayObject;
use Zend\Stdlib\Parameters;
use Core\Service\RemoteAddress;

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

    protected function setUp()
    {
        parent::setUp();
        $this->client = XMock::of(Client::class);
        $authorisedClassesService = $this->getMock(AuthorisedClassesService::class, [], [$this->client]);


        $this->request = new Request();
        $this->container = new NewVehicleContainer(new ArrayObject());
        $this->identityProvider = XMock::of(MotFrontendIdentityProviderInterface::class);

        $catalogService = new StubCatalogService();

        $ctrl = new CreateVehicleController(
            $authorisedClassesService,
            $catalogService,
            $this->identityProvider,
            $this->authorisationMock,
            $this->container,
            $this->request,
            $this->client
        );
        $this->setController($ctrl);

        $this->authorisationMock->granted([PermissionInSystem::VEHICLE_CREATE]);
        $authorisedClassesService->expects($this->any())
            ->method('getCombinedAuthorisedClassesForPersonAndVts')
            ->willReturn($this->getCombinedAuthorisedClasses());


        $this->expectIdentitySet();
    }

    private function expectIdentitySet()
    {
        $this->identityProvider->expects($this->any())->method('getIdentity')->willReturn(
            (new Identity())
                ->setCurrentVts((new VehicleTestingStation())->setVtsId(self::VTS_ID))
                ->setUserId(self::PERSON_ID)
        );
    }

    public function testIndex_givenEnteredIndex_shouldRedirect()
    {

        $query = ['reg' => 'RERG123', 'vin' => 'VIN1244'];
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
        $this->container->setStepOneFormData($this->createStepOneForm());
        $this->container->setStepTwoFormData($this->createStepTwoForm());
        $this->container->setApiData($this->dataCatalog());

        $this->client->expects($this->any())->method('get')->will(
            MultiCallStubBuilder::of()
                ->add(
                    UrlBuilder::person(self::PERSON_ID)->getMotTesting()->toString(),
                    $this->getMockAuthorisedClasses()
                )->add(UrlBuilder::vehicleDictionary()->make()->toString(), $this->makes())
                ->build()
        );

        $vm = $this->sut()->confirmAction();

        $this->assertNotNull($vm->getVariables()['sectionOneData']);
        $this->assertNotNull($vm->getVariables()['sectionTwoData']);
    }

    public function testConfirm_givenSubmittedConfirmation_shouldRedirect()
    {
        $expectedData = [
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
            'clientIp' => RemoteAddress::getIp()
        ];

        $this->request->setMethod('post');

        $this->container->setStepOneFormData($this->createStepOneForm());
        $this->container->setStepTwoFormData($this->createStepTwoForm());
        $this->container->setApiData($this->dataCatalog());

        $this->client->expects($this->any())->method('postJson')
            ->with(VehicleUrlBuilder::vehicle(), $expectedData);

        $this->redirectPluginMock
            ->expects(\PHPUnit_Framework_TestCase::once())
            ->method('toUrl');

        $this->sut()->confirmAction();

    }

    public function testAddStepTwo_givenEnteredTheStep_shouldReturnForm()
    {
        $this->request->setMethod('get');
        $this->container->setStepOneFormData($this->createStepOneForm());
        $this->container->setApiData($this->dataCatalog());

        $this->client->expects($this->any())->method('get')->will(
            MultiCallStubBuilder::of()
                ->add(
                    UrlBuilder::vehicleDictionary()->make(self::MAKE_ID)->model()->toString(),
                    $this->models()
                )
                ->add(
                    UrlBuilder::person(self::PERSON_ID)->getMotTesting()->toString(),
                    $this->getMockAuthorisedClasses()
                )
                ->build()
        );

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
        $this->container->setStepOneFormData($this->createStepOneForm());
        $this->container->setApiData($this->dataCatalog());

        $this->client->expects($this->any())->method("get")->will(
            MultiCallStubBuilder::of()
                ->add(
                    UrlBuilder::vehicleDictionary()->make(self::MAKE_ID)->model()->toString(),
                    $this->models()
                )
                ->add(
                    UrlBuilder::person(self::PERSON_ID)->getMotTesting()->toString(),
                    $this->getMockAuthorisedClasses()
                )
                ->build()
        );

        $this->expectRedirect(CreateVehicleController::ROUTE, ['action' => 'confirm']);
        $this->sut()->addStepTwoAction();
    }

    private function makes()
    {
        return ['data' => [['id' => 1, 'code' => 'BMW', 'name' => 'BMW']]];
    }

    private function models()
    {
        return ['data' => [['id' => 1, 'code' => 'Mini', 'name' => self::MODEL_ID]]];
    }

    private function getMockAuthorisedClasses()
    {
        return [
            'data' => [
                'class1' => AuthorisationForTestingMotStatusCode::QUALIFIED,
                'class2' => AuthorisationForTestingMotStatusCode::QUALIFIED,
                'class3' => AuthorisationForTestingMotStatusCode::QUALIFIED,
                'class4' => AuthorisationForTestingMotStatusCode::QUALIFIED,
                'class5' => AuthorisationForTestingMotStatusCode::QUALIFIED,
                'class6' => AuthorisationForTestingMotStatusCode::QUALIFIED,
                'class7' => AuthorisationForTestingMotStatusCode::QUALIFIED,
            ]
        ];
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
}
