<?php

namespace DvsaMotTest\Controller;

use Application\Service\ContingencySessionManager;
use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\Messages\InvalidTestStatus;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\DtoHydrator;
use DvsaMotTest\Data\BrakeTestResultsResource;
use DvsaMotTest\Mapper\BrakeTestConfigurationClass1And2Mapper;
use DvsaMotTest\Mapper\BrakeTestConfigurationClass3AndAboveMapper;
use DvsaMotTest\Mapper\BrakeTestConfigurationMapperInterface;
use DvsaMotTest\Model\BrakeTestConfigurationClass1And2Helper;
use DvsaMotTest\Model\BrakeTestConfigurationClass3AndAboveHelper;
use DvsaMotTest\Model\BrakeTestConfigurationHelperInterface;
use DvsaMotTest\Model\BrakeTestResultClass1And2ViewModel;
use DvsaMotTest\Model\BrakeTestResultClass3AndAboveViewModel;
use Zend\View\Model\ViewModel;

/**
 * Class BrakeTestResultsController
 */
class BrakeTestResultsController extends AbstractDvsaMotTestController
{
    const VEHICLE_TYPE_CHECK_START_DATE = '2010-09-01';

    const TEMPLATE_CONFIG_CLASS_1_2 = 'dvsa-mot-test/brake-test-results/brake-test-configuration-class12';
    const TEMPLATE_CONFIG_CLASS_3_AND_ABOVE
        = 'dvsa-mot-test/brake-test-results/brake-test-configuration-class3-and-above';
    const TEMPLATE_ADD_CLASS_1_2 = 'dvsa-mot-test/brake-test-results/add-brake-test-results-class12';
    const TEMPLATE_ADD_CLASS_3_AND_ABOVE = 'dvsa-mot-test/brake-test-results/add-brake-test-results-class3-and-above';

    const ROUTE_MOT_TEST_BRAKE_TEST_RESULTS = 'mot-test/brake-test-results';
    const ROUTE_MOT_TEST_BRAKE_TEST_SUMMARY = 'mot-test/brake-test-summary';

    public function configureBrakeTestAction()
    {
        $motTestNumber = $this->getMotTestNumber();
        /** @var MotTestDto $motTest */
        $motTest = $this->getMinimalMotTestFromApi($motTestNumber);

        $this->getPerformMotTestAssertion()->assertGranted($motTest);

        if ($motTest->getStatus() !== MotTestStatusName::ACTIVE) {
            $this->addErrorMessages([InvalidTestStatus::getMessage($motTest->getStatus())]);

            return $this->redirect()->toRoute(MotTestController::ROUTE_MOT_TEST, ['motTestNumber' => $motTestNumber]);
        }

        $isVehicleBikeType = $this->isMotTestVehicleBikeType($motTest);
        $configDtoMapper = $this->getBrakeTestMapperService($isVehicleBikeType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->getArrayCopy();
            $dto = $configDtoMapper->mapToDto($data);

            try {
                $this->postConfigurationDataToApiForValidation($dto, $motTest);
                return $this->redirectToAddBrakeTestResult($data, $motTestNumber);
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }
        } else {
            $dto = $configDtoMapper->mapToDefaultDto($motTest);
        }
        $configHelper = $this->getConfigHelperService($isVehicleBikeType);
        $configHelper->setConfigDto($dto);

        $viewModel = new ViewModel();
        $viewModel->setVariable(
            'isMotContingency',
            $this->getContingencySessionManager()->isMotContingency()
        );
        $viewModel->setVariable('brakeTestTypes', $this->getCatalogService()->getBrakeTestTypes());
        $viewModel->setVariable('motTest', $motTest);
        $viewModel->setVariable('showVehicleType', $this->displayConfigurationVehicleType($motTest));
        $viewModel->setVariable('configHelper', $configHelper);

        $viewModel->setTemplate($this->getConfigViewModelTemplate($isVehicleBikeType));

        return $viewModel;
    }

    private function postConfigurationDataToApiForValidation($dto, MotTestDto $motTest)
    {
        $apiUrl = UrlBuilder::of()->motTest()->routeParam('motTestNumber', $motTest->getMotTestNumber())
            ->brakeTestResult()->validateConfiguration();
        $this->getRestClient()->postJson($apiUrl, DtoHydrator::dtoToJson($dto));
    }

    private function redirectToAddBrakeTestResult(array $brakeTestConfiguration, $motTestNumber)
    {
        $brakeTestConfigurationContainer = $this->getServiceLocator()->get('BrakeTestConfigurationContainerHelper');
        $brakeTestConfigurationContainer->persistConfig($brakeTestConfiguration);
        return $this->redirect()->toRoute(
            self::ROUTE_MOT_TEST_BRAKE_TEST_RESULTS,
            ['motTestNumber' => $motTestNumber]
        );
    }

    public function addBrakeTestResultsAction()
    {
        $motTestNumber = $this->getMotTestNumber();
        /** @var MotTestDto $motTest */
        $motTest = $this->getMinimalMotTestFromApi($motTestNumber);

        if (!$motTest) {
            return $this->getFlashErrorViewModel();
        }

        $this->getPerformMotTestAssertion()->assertGranted($motTest);

        if ($this->isMotTestVehicleBikeType($motTest)) {
            return $this->addBrakeTestResultsClass12($motTest);
        }

        return $this->addBrakeTestResultsClass3AndAbove($motTest);
    }

    private function addBrakeTestResultsClass12(MotTestDto $motTest)
    {
        $request = $this->getRequest();

        $brakeTestResult = null;

        $brakeTestConfigurationContainer = $this->getServiceLocator()->get('BrakeTestConfigurationContainerHelper');
        $queryData = $brakeTestConfigurationContainer->fetchConfig();

        $configDto = (new BrakeTestConfigurationClass1And2Mapper)->mapToDto($queryData);

        if ($request->isPost()) {
            $brakeTestResult = new BrakeTestResultClass1And2ViewModel($configDto, $request->getPost()->getArrayCopy());

            try {
                $this->getBrakeTestResultsResource()->save($motTest->getMotTestNumber(), $brakeTestResult->toArray());

                return $this->redirect()->toRoute(
                    self::ROUTE_MOT_TEST_BRAKE_TEST_SUMMARY,
                    ['motTestNumber' => $motTest->getMotTestNumber()]
                );
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }
        } else {
            $brakeTestResult = new BrakeTestResultClass1And2ViewModel($configDto, $motTest->getBrakeTestResult());
        }

        $viewModel = new ViewModel(
            [
                'isMotContingency'       => $this->getContingencySessionManager()->isMotContingency(),
                'motTestNumber'          => $motTest->getMotTestNumber(),
                'vehicle'                => $motTest->getVehicle(),
                'brakeTestResult'        => $brakeTestResult,
                'brakeTestConfiguration' => $brakeTestResult->getBrakeTestConfiguration(),
            ]
        );
        $viewModel->setTemplate(self::TEMPLATE_ADD_CLASS_1_2);

        return $viewModel;
    }

    private function addBrakeTestResultsClass3AndAbove(MotTestDto $motTest)
    {
        /** @var VehicleDto $vehicle */
        $vehicle = $motTest->getVehicle();

        $request = $this->getRequest();

        $brakeTestResult = null;

        $brakeTestConfigurationContainer = $this->getServiceLocator()->get('BrakeTestConfigurationContainerHelper');
        $queryData = $brakeTestConfigurationContainer->fetchConfig();

        $configDto = (new BrakeTestConfigurationClass3AndAboveMapper)->mapToDto($queryData);

        if ($request->isPost()) {
            $brakeTestResult = new BrakeTestResultClass3AndAboveViewModel(
                $configDto,
                null,
                $request->getPost()->getArrayCopy()
            );

            try {
                $this->getBrakeTestResultsResource()->save($motTest->getMotTestNumber(), $brakeTestResult->toArray());

                return $this->redirect()->toRoute(
                    self::ROUTE_MOT_TEST_BRAKE_TEST_SUMMARY,
                    ['motTestNumber' => $motTest->getMotTestNumber()]
                );
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }
        } else {
            $brakeTestResult = new BrakeTestResultClass3AndAboveViewModel(
                $configDto,
                $motTest->getBrakeTestResult(),
                null
            );
        }

        $viewModel = new ViewModel(
            [
                'isMotContingency'       => $this->getContingencySessionManager()->isMotContingency(),
                'motTestNumber'          => $motTest->getMotTestNumber(),
                'vehicle'                => $vehicle,
                'brakeTestResult'        => $brakeTestResult,
                'brakeTestConfiguration' => $brakeTestResult->getBrakeTestConfiguration(),
            ]
        );
        $viewModel->setTemplate(self::TEMPLATE_ADD_CLASS_3_AND_ABOVE);

        return $viewModel;
    }

    public function displayBrakeTestSummaryAction()
    {
        $motTestNumber = $this->getMotTestNumber();
        /** @var MotTestDto $motTest */
        $motTest = $this->getMinimalMotTestFromApi($motTestNumber);

        $this->getPerformMotTestAssertion()->assertGranted($motTest);

        $showParkingBrakeImbalance
            = isset($motTest->getBrakeTestResult()['serviceBrakeIsSingleLine']) ?
            $motTest->getBrakeTestResult()['serviceBrakeIsSingleLine'] : false;
        $showAxleTwoParkingBrakeImbalance = $showParkingBrakeImbalance
            && !empty($motTest->getBrakeTestResult()['parkingBrakeSecondaryImbalance']);

        return new ViewModel(
            [
                'isMotContingency'                 => $this->getContingencySessionManager()->isMotContingency(),
                'motTest'                          => $motTest,
                'showParkingBrakeImbalance'        => $showParkingBrakeImbalance,
                'showAxleTwoParkingBrakeImbalance' => $showAxleTwoParkingBrakeImbalance,
            ]
        );
    }

    protected function getMotTestNumber()
    {
        return $this->params()->fromRoute('motTestNumber', null);
    }

    protected function isMotTestVehicleBikeType(MotTestDto $motTest)
    {
        return $this->isMotTestVehicleClass($motTest, VehicleClassCode::CLASS_1)
            || $this->isMotTestVehicleClass($motTest, VehicleClassCode::CLASS_2);
    }

    /**
     * @param MotTestDto $motTest
     * @param string     $expectedVehicleClassCode
     *
     * @return bool
     */
    protected function isMotTestVehicleClass(MotTestDto $motTest, $expectedVehicleClassCode)
    {
        return $this->getVehicleClassCodeFromMotTest($motTest) === $expectedVehicleClassCode;
    }

    protected function getVehicleClassCodeFromMotTest(MotTestDto $motTest)
    {
        $vehicle = $motTest->getVehicle();

        return $vehicle->getClassCode();
    }

    protected function displayConfigurationVehicleType(MotTestDto $motTest)
    {
        $vehicle = $motTest->getVehicle();
        $vehicleFirstUsedDate = new \DateTime($vehicle->getFirstUsedDate());
        $preVehicleTypeCheckDate = new \DateTime(self::VEHICLE_TYPE_CHECK_START_DATE);

        return $this->isMotTestVehicleClass($motTest, VehicleClassCode::CLASS_4)
            && $vehicleFirstUsedDate >= $preVehicleTypeCheckDate;
    }

    /**
     * @return BrakeTestResultsResource
     */
    private function getBrakeTestResultsResource()
    {
        return $this->getServiceLocator()->get(BrakeTestResultsResource::class);
    }

    /**
     * @return WebPerformMotTestAssertion
     */
    private function getPerformMotTestAssertion()
    {
        return $this->getServiceLocator()->get(WebPerformMotTestAssertion::class);
    }

    /**
     * @param bool $isVehicleBikeType
     *
     * @return BrakeTestConfigurationMapperInterface
     */
    private function getBrakeTestMapperService($isVehicleBikeType)
    {
        if ($isVehicleBikeType) {
            return $this->getServiceLocator()->get(BrakeTestConfigurationClass1And2Mapper::class);
        } else {
            return $this->getServiceLocator()->get(BrakeTestConfigurationClass3AndAboveMapper::class);
        }
    }

    /**
     * @param bool $isVehicleBikeType
     *
     * @return BrakeTestConfigurationHelperInterface
     */
    private function getConfigHelperService($isVehicleBikeType)
    {
        if ($isVehicleBikeType) {
            return $this->getServiceLocator()->get(BrakeTestConfigurationClass1And2Helper::class);
        } else {
            return $this->getServiceLocator()->get(BrakeTestConfigurationClass3AndAboveHelper::class);
        }
    }

    /**
     * @param bool $isVehicleBikeType
     *
     * @return string
     */
    private function getConfigViewModelTemplate($isVehicleBikeType)
    {
        if ($isVehicleBikeType) {
            return self::TEMPLATE_CONFIG_CLASS_1_2;
        } else {
            return self::TEMPLATE_CONFIG_CLASS_3_AND_ABOVE;
        }
    }

    /**
     * @return ContingencySessionManager
     */
    private function getContingencySessionManager()
    {
        return $this->serviceLocator->get(ContingencySessionManager::class);
    }
}
