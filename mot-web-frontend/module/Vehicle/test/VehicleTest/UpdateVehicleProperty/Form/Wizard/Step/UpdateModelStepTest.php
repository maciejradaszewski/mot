<?php

namespace VehicleTest\UpdateVehicleProperty\Form\Step;

use Core\Action\RedirectToRoute;
use Core\FormWizard\StepResult;
use CoreTest\FormWizard\Fake\FakeStep;
use Dvsa\Mot\ApiClient\Resource\Item\DvsaVehicle;
use DvsaCommon\ApiClient\Vehicle\Dictionary\Dto\ModelDto;
use DvsaCommon\ApiClient\Vehicle\Dictionary\ModelApiResource;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\Service\StartTestChangeService;
use stdClass;
use Vehicle\UpdateVehicleProperty\Context\UpdateVehicleContext;
use Vehicle\UpdateVehicleProperty\Form\MakeForm;
use Vehicle\UpdateVehicleProperty\Form\ModelForm;
use Vehicle\UpdateVehicleProperty\Form\OtherModelForm;
use Vehicle\UpdateVehicleProperty\Form\Wizard\Step\UpdateModelStep;
use Vehicle\UpdateVehicleProperty\ViewModel\Builder\VehicleEditBreadcrumbsBuilder;
use Zend\View\Helper\Url;

class UpdateModelStepTest extends \PHPUnit_Framework_TestCase
{
    private $url;
    private $modelApiResource;
    private $vehicleEditBreadcrumbsBuilder;
    private $formUuid;

    /** @var StartTestChangeService */
    private $startTestChangeService;

    protected function setUp()
    {
        $this->url = XMock::of(Url::class);
        $this->modelApiResource = XMock::of(ModelApiResource::class);
        $this->vehicleEditBreadcrumbsBuilder = XMock::of(VehicleEditBreadcrumbsBuilder::class);
        $this->startTestChangeService = XMock::of(StartTestChangeService::class);
        $this->modelApiResource->expects($this->any())->method('getList')->willReturn($this->getModelList());
        $this->vehicleEditBreadcrumbsBuilder->expects($this->any())->method('getVehicleEditBreadcrumbs')->willReturn(['vehicle', 'change']);

        $this->formUuid = uniqid();
    }

    public function testExecuteGetReturnsStepResult()
    {
        $step = $this->createUpdateModelStep();
        $result = $step->executeGet();

        $this->assertInstanceOf(StepResult::class, $result);
    }

    public function testExecuteGetReturnsModelFormIfMakeHasBeenSelected()
    {
        $dvsaVehicle = $this->createDvsaVehicle();
        $makeData = [
            'name' => $dvsaVehicle->getMake()->getName(),
            MakeForm::FIELD_MAKE_NAME => $dvsaVehicle->getMake()->getId(),
        ];

        $step = $this->createUpdateModelStep(true, $makeData);
        $result = $step->executeGet($this->formUuid);

        $form = $result->getViewModel()->getForm();

        $this->assertInstanceOf(ModelForm::class, $form);
    }

    public function testExecuteGetReturnsOtherModelFormIfOtherOptionBeenSelected()
    {
        $makeData = [
            'name' => MakeForm::OTHER_NAME,
            MakeForm::FIELD_MAKE_NAME => MakeForm::OTHER_ID,
        ];

        $step = $this->createUpdateModelStep(true, $makeData);
        $result = $step->executeGet($this->formUuid);

        $form = $result->getViewModel()->getForm();

        $this->assertInstanceOf(OtherModelForm::class, $form);
    }

    public function testPrepopulatesFormWithDefaultValuesWhenDataAreNotStoredInContainer()
    {
        $dvsaVehicle = $this->createDvsaVehicle();
        $makeData = [
            'name' => $dvsaVehicle->getMake()->getName(),
            MakeForm::FIELD_MAKE_NAME => $dvsaVehicle->getMake()->getId(),
        ];

        $step = $this->createUpdateModelStep(true, $makeData);

        $result = $step->executeGet($this->formUuid);

        /** @var ModelForm $form */
        $form = $result->getViewModel()->getForm();
        $modelId = $form->getModelElement()->getValue();

        $this->assertEquals($modelId, $this->createDvsaVehicle()->getModel()->getId());
    }

    public function testPrePopulatesFormChangeUnderTest_populatesWithEmptyValue()
    {
        $dvsaVehicle = $this->createDvsaVehicle();
        $makeData = [
            'name' => $dvsaVehicle->getMake()->getName(),
            MakeForm::FIELD_MAKE_NAME => $dvsaVehicle->getMake()->getId(),
        ];
        $updateMakeStep = new FakeStep('make step', true, $makeData);

        $context = new UpdateVehicleContext($dvsaVehicle, '1w', 'change-under-test');

        $step = new UpdateModelStep(
            $this->url,
            $this->modelApiResource,
            $this->vehicleEditBreadcrumbsBuilder,
            $this->startTestChangeService
        );
        $this->vehicleEditBreadcrumbsBuilder->expects($this->any())->method('getChangeVehicleUnderTestBreadcrumbs')->willReturn(['vehicle', 'change']);

        $step->setContext($context);
        $step->setPrevStep($updateMakeStep);

        $result = $step->executeGet($this->formUuid);

        /** @var ModelForm $form */
        $form = $result->getViewModel()->getForm();
        $modelId = $form->getModelElement()->getValue();

        $this->assertEquals($modelId, '');
    }

    public function testExecutePostReturnsStepResultIfDataAreInvalid()
    {
        $step = $this->createUpdateModelStep();

        $result = $step->executePost([], $this->formUuid);
        $this->assertInstanceOf(StepResult::class, $result);
    }

    public function testExecutePostRedirectsToNextStepIfDataAreValid()
    {
        $dvsaVehicle = $this->createDvsaVehicle();
        $makeData = [
            'name' => $dvsaVehicle->getMake()->getName(),
            MakeForm::FIELD_MAKE_NAME => $dvsaVehicle->getMake()->getId(),
        ];

        $step = $this->createUpdateModelStep(true, $makeData);
        $result = $step->executePost([ModelForm::FIELD_MODEL_NAME => $dvsaVehicle->getModel()->getId()], $this->formUuid);

        $this->assertInstanceOf(RedirectToRoute::class, $result);
        $this->assertEquals($step->getNextStep()->getRoute(['formUuid' => $this->formUuid]), $result);
    }

    public function testExecutePost_changeUnderTest_RedirectsToNextStepIfDataAreValid()
    {
        $dvsaVehicle = $this->createDvsaVehicle();
        $makeData = [
            'name' => $dvsaVehicle->getMake()->getName(),
            MakeForm::FIELD_MAKE_NAME => $dvsaVehicle->getMake()->getId(),
        ];

        $step = $this->createUpdateModelStep(true, $makeData, 'change-under-test');
        $routeName = $step->getRoute();
        $result = $step->executePost([ModelForm::FIELD_MODEL_NAME => $dvsaVehicle->getModel()->getId()], $this->formUuid);

        $this->assertInstanceOf(RedirectToRoute::class, $result);
        $this->assertEquals($step->getNextStep()->getRoute(['formUuid' => $this->formUuid]), $result);
        $this->assertSame('vehicle/detail/change-under-test/make-and-model', $routeName->getRouteName());
        $this->assertSame('model', $routeName->getRouteParams()['property']);
    }

    private function createUpdateModelStep($isValidMakeStep = true, $makeData = [], $route = 'change')
    {
        $updateMakeStep = new FakeStep('make step', $isValidMakeStep, $makeData);
        $reviewStep = new FakeStep('review step', false);

        $dvsaVehicle = $this->createDvsaVehicle();
        $context = new UpdateVehicleContext($dvsaVehicle, '1w', $route);

        $step = new UpdateModelStep(
            $this->url,
            $this->modelApiResource,
            $this->vehicleEditBreadcrumbsBuilder,
            $this->startTestChangeService
        );
        $step->setContext($context);
        $step->setPrevStep($updateMakeStep);
        $step->setNextStep($reviewStep);

        return $step;
    }

    private function getModelList()
    {
        return [
            (new ModelDto())->setId(1)->setName('A1i'),
            (new ModelDto())->setId(2)->setName('A2'),
            (new ModelDto())->setId(3)->setName('A3'),
            (new ModelDto())->setId(4)->setName('A4'),
            (new ModelDto())->setId(MakeForm::OTHER_ID)->setName(MakeForm::OTHER_NAME),
        ];
    }

    private function createDvsaVehicle()
    {
        $make = new stdClass();
        $make->id = 1;
        $make->name = 'Audi';

        $model = new stdClass();
        $model->id = 4;
        $model->name = 'A4';

        $fuel = new stdClass();
        $model->code = 'PE';
        $model->name = 'Petrol';

        $colour = new stdClass();
        $colour->code = 'L';
        $colour->name = 'Grey';

        $secondaryColour = new stdClass();
        $secondaryColour->code = 'W';
        $secondaryColour->name = 'Not Stated';

        $std = new stdClass();
        $std->make = $make;
        $std->model = $model;
        $std->registration = 'reg123XSW';
        $std->vin = 'VIN98798798';
        $std->vehicleClass = null;
        $std->fuelType = $fuel;
        $std->colour = $colour;
        $std->colourSecondary = $secondaryColour;

        return new DvsaVehicle($std);
    }
}
