<?php

namespace DvsaMotTest\Controller;

use Application\Service\ContingencySessionManager;
use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use Dvsa\Mot\ApiClient\Resource\Item\BrakeTestResultClass1And2;
use Dvsa\Mot\ApiClient\Resource\Item\BrakeTestResultClass3AndAbove;
use Dvsa\Mot\ApiClient\Resource\Item\DvsaVehicle;
use Dvsa\Mot\ApiClient\Resource\Item\MotTest;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationDtoInterface;
use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommon\Enum\WeightSourceCode;
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
use DvsaMotTest\View\Model\MotTestTitleModel;
use Zend\View\Model\ViewModel;

/**
 * Class BrakeTestResultsController.
 */
class BrakeTestResultsController extends AbstractDvsaMotTestController
{
    const VEHICLE_TYPE_CHECK_START_DATE = '2010-09-01';

    const TEMPLATE_CONFIG_CLASS_1_2 = 'dvsa-mot-test/brake-test-results/brake-test-configuration-class12';
    const TEMPLATE_CONFIG_CLASS_3_AND_ABOVE
        = 'dvsa-mot-test/brake-test-results/brake-test-configuration-class3-and-above';
    const TEMPLATE_ADD_CLASS_1_2 = 'dvsa-mot-test/brake-test-results/add-brake-test-results-class12';
    const TEMPLATE_ADD_CLASS_3_AND_ABOVE
        = 'dvsa-mot-test/brake-test-results/add-brake-test-results-class3-and-above';

    const ROUTE_MOT_TEST_BRAKE_TEST_RESULTS = 'mot-test/brake-test-results';
    const ROUTE_MOT_TEST_BRAKE_TEST_SUMMARY = 'mot-test/brake-test-summary';

    const BREAKS_PERFORMANCE_CATEGORY_ID_CLASS_1_AND_2 = 120;
    const BREAKS_PERFORMANCE_CATEGORY_ID = 5430;

    const PAGE_TITLE__BRAKE_TEST_CONFIGURATION = "Brake test configuration";

    // Vehicle Weight Type content on Brake Test Configuration page
    const VEHICLE_WEIGHT_TYPE__BRAKE_TEST_WEIGHT = 'Brake test weight (from manufacturer or other reliable data)';
    const VEHICLE_WEIGHT_TYPE__PRESENTED_WEIGHT = 'Presented weight (measured during ATL brake test)';
    const VEHICLE_WEIGHT_TYPE__NOT_KNOWN = 'Not known';
    const VEHICLE_WEIGHT_TYPE__DGW_MAM = 'DGW (design gross weight from manufacturers plate) or MAM (maximum authorised mass)';
    const VEHICLE_WEIGHT_TYPE__CALCULATED_WEIGHT = 'Calculated weight (see the MOT inspection manual for the calculation)';
    const VEHICLE_WEIGHT_TYPE__DGW = 'DGW (design gross weight from manufacturers plate)';

    public function configureBrakeTestAction()
    {
        $request = $this->getRequest();
        $motTestNumber = $this->getMotTestNumber();

        /** @var MotTest $motTest */
        $motTest = $this->tryGetMotTestOrAddErrorMessages($motTestNumber);
        /** @var DvsaVehicle $vehicle */
        $vehicle = $this->getVehicleServiceClient()->getDvsaVehicleByIdAndVersion($motTest->getVehicleId(), $motTest->getVehicleVersion());
        $isVehicleBikeType = $this->isMotTestVehicleBikeType($vehicle->getVehicleClass()->getCode());
        $brakeTestResult = null;

        if ($request->isPost()) {
            $configDtoMapper = $this->getBrakeTestMapperService($isVehicleBikeType);

            $data = $request->getPost()->getArrayCopy();
            $dto = $configDtoMapper->mapToDto($data);

            try {
                $this->postConfigurationDataToApiForValidation($dto, $motTestNumber);

                return $this->redirectToAddBrakeTestResult($data, $motTestNumber);
            } catch (RestApplicationException $e) {
                if ($e->containsError(InvalidTestStatus::getMessage(MotTestStatusName::ABORTED))) {
                    $this->addErrorMessages([InvalidTestStatus::getMessage(MotTestStatusName::ABORTED)]);

                    return $this->redirect()->toRoute(MotTestController::ROUTE_MOT_TEST, ['motTestNumber' => $motTestNumber]);
                } elseif ($e->containsError(InvalidTestStatus::getMessage(MotTestStatusName::FAILED))) {
                    $this->addErrorMessages([InvalidTestStatus::getMessage(MotTestStatusName::FAILED)]);

                    return $this->redirect()->toRoute(MotTestController::ROUTE_MOT_TEST, ['motTestNumber' => $motTestNumber]);
                }
                $this->addErrorMessages($e->getDisplayMessages());
            }
        }
        
        $this->getPerformMotTestAssertion()->assertGranted($motTest);

        $brakeTestResultData = $motTest->getBrakeTestResult();

        if ($brakeTestResultData !== null) {
            if ($vehicle->getVehicleClass()->getCode() !== VehicleClassCode::CLASS_1 && $vehicle->getVehicleClass()->getCode() !== VehicleClassCode::CLASS_2) {
                $brakeTestResult = new BrakeTestResultClass3AndAbove($brakeTestResultData);
            } else {
                $brakeTestResult = new BrakeTestResultClass1And2($brakeTestResultData);
            }
        }

        if ($motTest->getStatus() !== MotTestStatusName::ACTIVE) {
            $this->addErrorMessages([InvalidTestStatus::getMessage($motTest->getStatus())]);

            return $this->redirect()->toRoute(MotTestController::ROUTE_MOT_TEST, ['motTestNumber' => $motTestNumber]);
        }

        if (!$request->isPost()) {
            $configDtoMapper = $this->getBrakeTestMapperService($isVehicleBikeType);
            $dto = $configDtoMapper->mapToDefaultDto($motTest, $vehicle->getVehicleClass()->getCode());
        }

        $configHelper = $this->getConfigHelperService($isVehicleBikeType);
        $configHelper->setConfigDto($dto);

        $preselectBrakeTestWeight = false;
        if ($brakeTestResult !== null && $vehicle->getVehicleClass()->getCode() !== VehicleClassCode::CLASS_1 && $vehicle->getVehicleClass()->getCode() !== VehicleClassCode::CLASS_2) {
            $hasCorrectService1BrakeTestType = in_array($brakeTestResult->getServiceBrake1TestType(), [BrakeTestTypeCode::PLATE, BrakeTestTypeCode::ROLLER]);
            $hasCorrectService2BrakeTestType = in_array($brakeTestResult->getServiceBrake2TestType(), [BrakeTestTypeCode::PLATE, BrakeTestTypeCode::ROLLER]);
            $hasCorrectParkingBrakeTestType = in_array($brakeTestResult->getParkingBrakeTestType(), [BrakeTestTypeCode::PLATE, BrakeTestTypeCode::ROLLER]);

            if ($brakeTestResult->getVehicleWeight() && ($hasCorrectService1BrakeTestType || $hasCorrectService2BrakeTestType || $hasCorrectParkingBrakeTestType)) {
                $preselectBrakeTestWeight = true;
            }
        }

        $brakeTestConfigurationViewModel = new ViewModel();
        $brakeTestConfigurationViewModel->setVariables([
            'isMotContingency' => $this->getContingencySessionManager()->isMotContingency(),
            'brakeTestTypes' => array_reverse($this->getCatalogService()->getBrakeTestTypes()),
            'motTest' => $motTest,
            'configHelper' => $configHelper,
            'motTestTitleViewModel' => (new MotTestTitleModel()),
            'vehicle' => $vehicle,
            'vehicleClass' => $vehicle->getVehicleClass()->getCode(),
            'title' => self::PAGE_TITLE__BRAKE_TEST_CONFIGURATION,
        ]);

        if ($isVehicleBikeType && $brakeTestResult !== null) {
            $brakeTestConfigurationViewModel->setVariables([
                'brakeTestType' => $brakeTestResult->getBrakeTestTypeCode(),
                'vehicleWeightFront' => $brakeTestResult->getVehicleWeightFront(),
                'vehicleWeightRear' => $brakeTestResult->getVehicleWeightRear(),
                'riderWeight' => $brakeTestResult->getRiderWeight(),
                'isSidecarAttached' => $brakeTestResult->getSidecarWeight() ? null : false,
                'sidecarWeight' => $brakeTestResult->getSidecarWeight(),
            ]);
        }

        // If this is a Group A vehicle (Class 1 or 2)
        if ($isVehicleBikeType) {
            $brakeTestConfigurationViewModel->setTemplate(self::TEMPLATE_CONFIG_CLASS_1_2);
        }
        // Else if this is a Group B vehicle (Class 3, 4, 5 or 7)
        else {
            $brakeTestConfigurationViewModel->setTemplate(self::TEMPLATE_CONFIG_CLASS_3_AND_ABOVE);
            $brakeTestConfigurationViewModel->setVariables([
                    'showVehicleType' => $this->displayConfigurationVehicleType($vehicle),
                    'weightTypes' => $this->getGroupBWeightTypes($vehicle->getVehicleClass()->getCode()),
                    'preselectBrakeTestWeight' => $preselectBrakeTestWeight,
                ]
            );
        }

        return $brakeTestConfigurationViewModel;
    }

    /**
     * @param string $vehicleClass
     *
     * @return array
     */
    private function getGroupBWeightTypes($vehicleClass)
    {
        switch ($vehicleClass) {
            case VehicleClassCode::CLASS_3:
            case VehicleClassCode::CLASS_4:
                return [
                    WeightSourceCode::VSI            => self::VEHICLE_WEIGHT_TYPE__BRAKE_TEST_WEIGHT,
                    WeightSourceCode::PRESENTED      => self::VEHICLE_WEIGHT_TYPE__PRESENTED_WEIGHT,
                    WeightSourceCode::NOT_APPLICABLE => self::VEHICLE_WEIGHT_TYPE__NOT_KNOWN,
                ];
            case VehicleClassCode::CLASS_5:
                return [
                    WeightSourceCode::DGW_MAM    => self::VEHICLE_WEIGHT_TYPE__DGW_MAM,
                    WeightSourceCode::CALCULATED => self::VEHICLE_WEIGHT_TYPE__CALCULATED_WEIGHT,
                ];
            case VehicleClassCode::CLASS_7:
                return [
                    WeightSourceCode::DGW       => self::VEHICLE_WEIGHT_TYPE__DGW,
                    WeightSourceCode::PRESENTED => self::VEHICLE_WEIGHT_TYPE__PRESENTED_WEIGHT,
                ];
            default:
                throw new \InvalidArgumentException(sprintf("Unrecognised Group B vehicle class: '%s'", $vehicleClass));
        }
    }

    /**
     * @param BrakeTestConfigurationDtoInterface $dto
     * @param int                                $motTestNumber
     */
    private function postConfigurationDataToApiForValidation($dto, $motTestNumber)
    {
        $apiUrl = UrlBuilder::of()->motTest()->routeParam('motTestNumber', $motTestNumber)
            ->brakeTestResult()->validateConfiguration();
        $this->getRestClient()->postJson($apiUrl, DtoHydrator::dtoToJson($dto));
    }

    /**
     * @param array $brakeTestConfiguration
     * @param int   $motTestNumber
     *
     * @return \Zend\Http\Response
     */
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
        $request = $this->getRequest();
        $motTestNumber = $this->getMotTestNumber();
        /** @var MotTest $motTest */
        $motTest = $this->tryGetMotTestOrAddErrorMessages($motTestNumber);

        /** @var DvsaVehicle $vehicle */
        $vehicle = $this->getVehicleServiceClient()->getDvsaVehicleByIdAndVersion($motTest->getVehicleId(), $motTest->getVehicleVersion());

        if ($request->isPost()) {
            $vehicleClass = $request->getPost()->get('vehicleClass');
        }
        else {
            if (!$motTest) {
                return $this->getFlashErrorViewModel();
            }

            $this->getPerformMotTestAssertion()->assertGranted($motTest);
            $vehicleClass = $vehicle->getVehicleClass()->getCode();
        }

        if ($this->isMotTestVehicleBikeType($vehicleClass)) {
            return $this->addBrakeTestResultsClass12($motTest, $vehicle);
        }

        return $this->addBrakeTestResultsClass3AndAbove($motTest, $vehicle);
    }

    /**
     * @param MotTest|null     $motTest
     * @param DvsaVehicle|null $vehicle
     *
     * @return \Zend\Http\Response|ViewModel
     */
    private function addBrakeTestResultsClass3AndAbove(MotTest $motTest = null, DvsaVehicle $vehicle = null)
    {
        $request = $this->getRequest();
        $motTestNumber = $this->getMotTestNumber();

        $brakeTestResult = null;

        $brakeTestConfigurationContainer = $this->getServiceLocator()->get('BrakeTestConfigurationContainerHelper');
        $queryData = $brakeTestConfigurationContainer->fetchConfig();

        $configDto = (new BrakeTestConfigurationClass3AndAboveMapper())->mapToDto($queryData);

        if ($request->isPost()) {
            $brakeTestResult = new BrakeTestResultClass3AndAboveViewModel(
                $configDto,
                null,
                null,
                $request->getPost()->getArrayCopy()
            );

            try {
                $this->getBrakeTestResultsResource()->save($motTestNumber, $brakeTestResult->toArray());

                return $this->redirect()->toRoute(
                    self::ROUTE_MOT_TEST_BRAKE_TEST_SUMMARY,
                    ['motTestNumber' => $motTestNumber]
                );
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }
        } else {
            $brakeTestResult = new BrakeTestResultClass3AndAboveViewModel(
                $configDto,
                null,
                null
            );
        }

        if (!$motTest) {
            /** @var MotTest $motTest */
            $motTest = $this->tryGetMotTestOrAddErrorMessages($motTestNumber);
        }

        return $this->createViewModel(self::TEMPLATE_ADD_CLASS_3_AND_ABOVE, [
                'isMotContingency'       => $this->getContingencySessionManager()->isMotContingency(),
                'motTestNumber'          => $motTestNumber,
                'vehicle'                => $vehicle,
                'brakeTestResult'        => $brakeTestResult,
                'brakeTestConfiguration' => $brakeTestResult->getBrakeTestConfiguration(),
                'motTestTitleViewModel' => (new MotTestTitleModel()),
                'motTest' => $motTest,
            ]
        );
    }

    /**
     * @param MotTest|null     $motTest
     * @param DvsaVehicle|null $vehicle
     *
     * @return \Zend\Http\Response|ViewModel
     */
    private function addBrakeTestResultsClass12(MotTest $motTest = null, DvsaVehicle $vehicle = null)
    {
        $request = $this->getRequest();
        $motTestNumber = $this->getMotTestNumber();

        $brakeTestResult = null;

        $brakeTestConfigurationContainer = $this->getServiceLocator()->get('BrakeTestConfigurationContainerHelper');
        $queryData = $brakeTestConfigurationContainer->fetchConfig();

        $configDto = (new BrakeTestConfigurationClass1And2Mapper())->mapToDto($queryData);

        if ($request->isPost()) {
            $brakeTestResult = new BrakeTestResultClass1And2ViewModel(
                $configDto,
                null,
                $request->getPost()->getArrayCopy());

            try {
                $this->getBrakeTestResultsResource()->save($motTestNumber, $brakeTestResult->toArray());

                return $this->redirect()->toRoute(
                    self::ROUTE_MOT_TEST_BRAKE_TEST_SUMMARY,
                    ['motTestNumber' => $motTestNumber]
                );
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }
        } else {
            $brakeTestResult = new BrakeTestResultClass1And2ViewModel(
                $configDto,
                null,
                $request->getPost()->getArrayCopy());
        }

        if (!$motTest) {
            /** @var MotTest $motTest */
            $motTest = $this->tryGetMotTestOrAddErrorMessages($motTestNumber);
        }

        return $this->createViewModel(self::TEMPLATE_ADD_CLASS_1_2, [
                'isMotContingency'       => $this->getContingencySessionManager()->isMotContingency(),
                'motTestNumber'          => $motTestNumber,
                'vehicle'                => $vehicle,
                'brakeTestResult'        => $brakeTestResult,
                'brakeTestConfiguration' => $brakeTestResult->getBrakeTestConfiguration(),
                'motTestTitleViewModel' => (new MotTestTitleModel()),
                'motTest' => $motTest,
            ]
        );
    }

    public function displayBrakeTestSummaryAction()
    {
        $motTestNumber = $this->getMotTestNumber();
        /** @var MotTest $motTest */
        $motTest = $this->tryGetMotTestOrAddErrorMessages($motTestNumber);

        /** @var DvsaVehicle $vehicle */
        $vehicle = $this->getVehicleServiceClient()->getDvsaVehicleByIdAndVersion($motTest->getVehicleId(), $motTest->getVehicleVersion());

        /** @var DvsaVehicleViewModel $vehicleViewModel */
        $vehicleViewModel = new DvsaVehicleViewModel($vehicle);

        $this->getPerformMotTestAssertion()->assertGranted($motTest);

        if (in_array($vehicleViewModel->getVehicleClass()->getCode(), [VehicleClassCode::CLASS_1, VehicleClassCode::CLASS_2])) {
            $showParkingBrakeImbalance = false;
            $showAxleTwoParkingBrakeImbalance = false;
        } else {
            $brakeTestResultClass3 = new BrakeTestResultClass3AndAbove($motTest->getBrakeTestResult());

            $showParkingBrakeImbalance = $brakeTestResultClass3->getServiceBrakeIsSingleLine();

            $showAxleTwoParkingBrakeImbalance = $showParkingBrakeImbalance
                && $brakeTestResultClass3->getParkingBrakeSecondaryImbalance();
        }

        $brakeRfrId = $this->getBreaksPerfomanceCategoryId($motTest, $vehicle);
        $breakPerformanceDefectsUrl = $this->getBreaksPerformanceDefectsUrl($brakeRfrId, $motTestNumber);

        return new ViewModel(
            [
                'isMotContingency'                 => $this->getContingencySessionManager()->isMotContingency(),
                'vehicleViewModel'                 => $vehicleViewModel,
                'motTest'                          => $motTest,
                'showParkingBrakeImbalance'        => $showParkingBrakeImbalance,
                'showAxleTwoParkingBrakeImbalance' => $showAxleTwoParkingBrakeImbalance,
                'motTestTitleViewModel' => (new MotTestTitleModel()),
                'breakPerformanceDefectsUrl' => $breakPerformanceDefectsUrl,
            ]
        );
    }

    protected function getMotTestNumber()
    {
        return $this->params()->fromRoute('motTestNumber', null);
    }

    /**
     * @param string $vehicleClass
     *
     * @return bool
     */
    protected function isMotTestVehicleBikeType($vehicleClass)
    {
        return $vehicleClass == VehicleClassCode::CLASS_1
            || $vehicleClass == VehicleClassCode::CLASS_2;
    }

    /**
     * @param DvsaVehicle $vehicle
     * @param string      $expectedVehicleClassCode
     *
     * @return bool
     */
    protected function isMotTestVehicleClass(DvsaVehicle $vehicle, $expectedVehicleClassCode)
    {
        return $this->getVehicleClassCodeFromMotTest($vehicle) === $expectedVehicleClassCode;
    }

    /**
     * @param DvsaVehicle $motTest
     *
     * @return string
     */
    protected function getVehicleClassCodeFromMotTest(DvsaVehicle $vehicle)
    {
        return $vehicle->getVehicleClass()->getCode();
    }

    /**
     * @param DvsaVehicle $motTest
     *
     * @return bool
     */
    protected function displayConfigurationVehicleType(DvsaVehicle $vehicle)
    {
        $vehicleFirstUsedDate = new \DateTime($vehicle->getFirstUsedDate());
        $preVehicleTypeCheckDate = new \DateTime(self::VEHICLE_TYPE_CHECK_START_DATE);

        return $this->isMotTestVehicleClass($vehicle, VehicleClassCode::CLASS_4)
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
     * @return ContingencySessionManager
     */
    private function getContingencySessionManager()
    {
        return $this->serviceLocator->get(ContingencySessionManager::class);
    }

    /**
     * Generate URL for breaks performance defects category.
     *
     * @param int $brakeRfrId
     * @param int $motTestNumber
     *
     * @return string
     */
    private function getBreaksPerformanceDefectsUrl($brakeRfrId, $motTestNumber)
    {
        $isNewDefectsFfEnabled = $this->getFeatureToggles()->isEnabled(FeatureToggle::TEST_RESULT_ENTRY_IMPROVEMENTS);

        // New defect category view
        if ($isNewDefectsFfEnabled === true) {
            return $this->url()->fromRoute(
                'mot-test-defects/categories/category',
                [
                    'categoryId' => $brakeRfrId,
                    'motTestNumber' => $motTestNumber,
                ]
            );
        }

        // Old defect category view
        return $this->url()->fromRoute(
            'mot-test/test-item-selector',
            [
                'tis-id' => $brakeRfrId,
                'motTestNumber' => $motTestNumber,
            ]
        );
    }

    /**
     * Determinate the defects category ID based on vehicle class.
     *
     * @param MotTest     $motTest
     * @param DvsaVehicle $dvsaVehicle
     *
     * @return int
     */
    private function getBreaksPerfomanceCategoryId(MotTest $motTest, DvsaVehicle $dvsaVehicle)
    {
        $vehicleClass = $dvsaVehicle->getVehicleClass()->getCode();

        if (in_array($vehicleClass, [VehicleClassCode::CLASS_1, VehicleClassCode::CLASS_2])) {
            return self::BREAKS_PERFORMANCE_CATEGORY_ID_CLASS_1_AND_2;
        }

        return self::BREAKS_PERFORMANCE_CATEGORY_ID;
    }

    /**
     * @param string $template
     * @param array  $variables
     *
     * @return ViewModel
     */
    private function createViewModel($template, array $variables)
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate($template);
        $viewModel->setVariables($variables);

        return $viewModel;
    }
}
