<?php

namespace DvsaMotTest\Controller;

use Application\Helper\PrgHelper;
use Application\Service\CatalogService;
use Application\Service\ContingencySessionManager;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\Assertion\RefuseToTestAssertion;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\Network;
use DvsaCommon\Dto\Common\ColourDto;
use DvsaCommon\Dto\MotTesting\ContingencyMotTestDto;
use DvsaCommon\Dto\Vehicle\AbstractVehicleDto;
use DvsaCommon\Dto\Vehicle\VehicleParamDto;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\HttpRestJson\Exception\OtpApplicationException;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\UrlBuilder\MotTestUrlBuilder;
use DvsaCommon\UrlBuilder\MotTestUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;
use DvsaMotTest\Constants\VehicleSearchSource;
use Vehicle\Helper\ColoursContainer;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class StartTestConfirmationController.
 */
class StartTestConfirmationController extends AbstractDvsaMotTestController
{
    const ROUTE_START_TEST_CONFIRMATION   = 'start-test-confirmation';
    const ROUTE_START_RETEST_CONFIRMATION = 'start-retest-confirmation';
    const ROUTE_PARAM_NO_REG              = 'noRegistration';
    const ROUTE_PARAM_ID                  = 'id';
    const ROUTE_PARAM_SOURCE              = 'source';
    const RETEST_GRANTED_CHECK_RESULT     = 0;

    /** @var Request */
    protected $request;
    protected $vehicleId;

    /** @var string */
    protected $obfuscatedVehicleId;
    protected $noRegistration;
    protected $vehicleSource;
    protected $vtsId;

    /** @var string mot test type */
    protected $method;
    protected $eligibilityNotices;
    /** @var AbstractVehicleDto */
    protected $vehicleDetails;
    protected $inProgressTestExists;

    protected $isEligibleForRetest;

    /** @var \DvsaCommon\Obfuscate\ParamObfuscator */
    protected $paramObfuscator;

    /** @var  PrgHelper */
    private $prgHelper;

    /** @param \DvsaCommon\Obfuscate\ParamObfuscator $paramObfuscator */
    public function __construct(ParamObfuscator $paramObfuscator)
    {
        $this->paramObfuscator = $paramObfuscator;
    }

    public function indexAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $method  = $request->getPost('retest') ? MotTestTypeCode::RE_TEST : MotTestTypeCode::NORMAL_TEST;

        return $this->commonAction($method);
    }

    public function retestAction()
    {
        return $this->commonAction(MotTestTypeCode::RE_TEST);
    }

    public function demoAction()
    {
        return $this->commonAction(MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING);
    }

    /**
     * @param string $method MOT_TEST_TYPE
     *
     * @return \Zend\Http\Response|ViewModel
     */
    protected function commonAction($method)
    {
        $this->prgHelper = new PrgHelper($this->request);
        if ($this->prgHelper->isRepeatPost()) {
            return $this->redirect()->toUrl($this->prgHelper->getRedirectUrl());
        }

        if ($method !== MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING) {
            $this->assertGranted(PermissionInSystem::MOT_TEST_START);
        }

        $this->method  = $method;
        $this->request = $this->getRequest();

        $this->processParams();
        $this->getVehicleDetails();
        $this->findIfInProgressTestExists();

        return $this->processRequest();
    }

    /**
     * @throws \Exception
     */
    protected function processParams()
    {
        $this->obfuscatedVehicleId = (string) $this->params()->fromRoute(self::ROUTE_PARAM_ID, 0);

        $this->vehicleId = $this->paramObfuscator->deobfuscateEntry(
            ParamObfuscator::ENTRY_VEHICLE_ID, $this->obfuscatedVehicleId, false
        );

        $noRegistrationString = $this->params()->fromRoute(self::ROUTE_PARAM_NO_REG);
        $this->noRegistration = ($noRegistrationString === '1');

        $this->vehicleSource = $this->params()->fromRoute(self::ROUTE_PARAM_SOURCE);

        if ($this->method === MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING) {
            $this->vtsId = null;

            return;
        }

        $currentVts = $this->getIdentity()->getCurrentVts();
        if (!$currentVts) {
            throw new \Exception("VTS not found");
        }
        $this->vtsId = $currentVts->getVtsId();
    }

    /**
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    protected function processRequest()
    {
        if ($this->request->isPost()) {
            $newTestData = null;

            $contingencySessionManager = $this->getContingencySessionManager();

            try {
                $pagingFromStartTestConfirm = $this->request->getPost('startTestConfirm');
                $newTestData                = $this->prepareNewTestDataFromPost();
                $newMotTestId               = $this->createNewTestFromPost($newTestData);

                $url = (
                    ($this->method === MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING
                    || $contingencySessionManager->isMotContingency())
                    ? MotTestUrlBuilderWeb::motTest($newMotTestId)
                    : MotTestUrlBuilderWeb::options($newMotTestId)
                );

                $this->prgHelper->setRedirectUrl($url->toString());

                return $this->redirect()->toUrl($url);
            } catch (OtpApplicationException $e) {
                if (isset($pagingFromStartTestConfirm)) {
                    $errorData = null;
                } else {
                    $errorData = $e->getErrorData();

                    if (isset($errorData['message'])) {
                        $message = $errorData['message'];
                        $this->addErrorMessages($message);
                    }
                }

                $this->applyVehicleChanges($newTestData);

                return $this->createOtpViewModel($errorData);
            } catch (RestApplicationException $e) {
                if ($this->isRetest() && ($e instanceof ValidationException)) {
                    $this->isEligibleForRetest = false;
                    $this->eligibilityNotices  = $e->getDisplayMessages();
                } else {
                    $this->addErrorMessages($e->getDisplayMessages());
                }
            }
        }

        return $this->createViewModel();
    }

    protected function createNewTestFromPost($newTestData)
    {
        if ($this->isRetest()) {
            $apiUrl = MotTestUrlBuilder::retest();
        } elseif ($this->method === MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING) {
            $apiUrl = MotTestUrlBuilder::demoTest();
        } else {
            $apiUrl = MotTestUrlBuilder::motTest();
        }

        $result = $this->getRestClient()->post($apiUrl->toString(), $newTestData);

        $createMotTestResult = $result['data'];

        return $createMotTestResult['motTestNumber'];
    }

    /**
     * @return array|null Array of new Test data or Null if was not POST
     */
    protected function prepareNewTestDataFromPost()
    {
        $request = $this->request;

        $vehicleIdKey = $this->isVehicleSource(VehicleSearchSource::DVLA) ? 'dvlaVehicleId' : 'vehicleId';

        $data = [
            $vehicleIdKey             => $this->vehicleId,
            'vehicleTestingStationId' => $this->vtsId,
            'primaryColour'           => $request->getPost('primaryColour'),
            'secondaryColour'         => $request->getPost('secondaryColour'),
            'fuelTypeId'              => $request->getPost('fuelType'),
            'vehicleClassCode'        => intval($request->getPost('vehicleClass')),
            'hasRegistration'         => !$this->noRegistration,
            'oneTimePassword'         => $request->getPost('oneTimePassword'),
        ];

        $contingencySessionManager = $this->getContingencySessionManager();
        if ($contingencySessionManager->isMotContingency()) {
            $contingencySession = $contingencySessionManager->getContingencySession();

            $data += [
                'contingencyId'     => $contingencySession['contingencyId'],
                'contingencyDto'    => DtoHydrator::dtoToJson($contingencySession['dto']),
            ];
        }

        return $data;
    }



    /**
     * @return array
     */
    protected function prepareViewData()
    {
        $viewData = [
            'method'               => $this->method,
            'vehicleDetails'       => $this->vehicleDetails,
            'id'                   => $this->obfuscatedVehicleId,
            'noRegistration'       => $this->noRegistration,
            'checkExpiryResults'   => null,
            'staticData'           => $this->getStaticData(),
            'source'               => $this->vehicleSource,
            'isMotContingency'     => $this->getContingencySessionManager()->isMotContingency(),
            'inProgressTestExists' => $this->inProgressTestExists,
            'canRefuseToTest'      => false,
            'isEligibleForRetest'  => false,
            'prgHelper'            => $this->prgHelper,
            'vin'                  => '',
            'registration'         => '',
            'searchVrm'            => $this->params()->fromQuery('searchVrm', ''),
            'searchVin'            => $this->params()->fromQuery('searchVin', '')
        ];

        $isReTest     = ($this->isRetest());
        $isNormalTest = ($this->method === MotTestTypeCode::NORMAL_TEST);

        if (!$isReTest) {
            $viewData['vin'] = $this->params()->fromQuery('vin', '');
            $viewData['registration'] = $this->params()->fromQuery('registration', '');
        }

        //  --  check eligibility for retest  --
        if ($isReTest
            || ($isNormalTest && $this->isVehicleSource(VehicleSearchSource::VTR))
        ) {
            //  --  to prevent double check at API after POST   --
            if ($this->isEligibleForRetest === null) {
                $this->checkEligibilityForRetest();
            }

            $viewData['isEligibleForRetest'] = $this->isEligibleForRetest;
            $viewData['eligibilityNotices']  = $this->eligibilityNotices;
        }

        //  --  process method specific parameters  --
        if ($isReTest || $isNormalTest) {
            //  --  get expire data   --
            $viewData['checkExpiryResults'] = $this->getCheckExpiryResults();

            //  --  ability to refuse    --
            $viewData['canRefuseToTest'] = (
                ($isNormalTest || ($isReTest && $this->isEligibleForRetest))
                && !$this->inProgressTestExists
                && $this->createRefuseToTestAssertion()->isGranted($this->vtsId)
            );
        }

        return $viewData;
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    protected function createViewModel()
    {
        $viewModel = new ViewModel($this->prepareViewData());

        if (in_array(
            $this->method,
            [
                MotTestTypeCode::NORMAL_TEST,
                MotTestTypeCode::RE_TEST,
                MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING,
            ],
            true
        )
        ) {
            $viewModel->setTemplate('dvsa-mot-test/start-test-confirmation/index.phtml');
        }

        return $viewModel;
    }

    /**
     * @param $otpErrorData
     *
     * @return ViewModel
     */
    protected function createOtpViewModel($otpErrorData)
    {
        $viewModel = $this->createViewModel();

        $message      = false;
        $shortMessage = false;

        if (isset($otpErrorData['message'])) {
            $message = $otpErrorData['message'];
        }

        if (isset($otpErrorData['shortMessage'])) {
            $shortMessage = $otpErrorData['shortMessage'];
        }

        $viewModel->setVariable('otpErrorMessage', $message);
        $viewModel->setVariable('otpErrorShortMessage', $shortMessage);

        $viewModel->setTemplate('dvsa-mot-test/start-test-confirmation/vehicle-change-confirmation.phtml');

        return $viewModel;
    }

    protected function isVehicleSource($type)
    {
        return ($this->vehicleSource === $type);
    }

    protected function applyVehicleChanges($newTestData)
    {
        $this->vehicleDetails
            ->setColour((new ColourDto())->setCode($newTestData['primaryColour']))
            ->setColourSecondary((new ColourDto())->setCode($newTestData['secondaryColour']))
            ->setVehicleClass((new VehicleClassDto())->setCode($newTestData['vehicleClassCode']))
            ->setFuelType((new VehicleParamDto())->setCode($newTestData['fuelTypeId']));
    }

    protected function getVehicleDetails()
    {
        /** @var MapperFactory $mapperFactory */
        $mapperFactory = $this->getServiceLocator()->get(MapperFactory::class);

        if ($this->isVehicleSource(VehicleSearchSource::DVLA)) {
            $apiResult = $mapperFactory->Vehicle->getDvlaById($this->vehicleId);
        } else {
            $apiResult = $mapperFactory->Vehicle->getById($this->vehicleId);
        }

        if ($apiResult instanceof AbstractVehicleDto) {
            $this->vehicleDetails = $apiResult;
        }
    }

    protected function findIfInProgressTestExists()
    {
        if ($this->isVehicleSource(VehicleSearchSource::DVLA)
            || $this->method === MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING
        ) {
            return;
        }

        $apiUrl    = VehicleUrlBuilder::vehicle($this->vehicleId)->testInProgressCheck();
        $apiResult = $this->getRestClient()->get($apiUrl);

        if (!empty($apiResult['data'])) {
            $this->inProgressTestExists = $apiResult['data'];
        }
    }

    /**
     * @param bool $isDvlaVehicle
     */
    protected function getCheckExpiryResults()
    {
        $isDvlaVehicle = ($this->vehicleDetails ? (bool) $this->vehicleDetails->isDvla() : false);

        $apiUrl    = VehicleUrlBuilder::testExpiryCheck($this->vehicleId, $isDvlaVehicle);

        $contingencySessionManager = $this->getContingencySessionManager();
        if ($contingencySessionManager->isMotContingency() === true) {
            /** @var ContingencyMotTestDto $contingency */
            $contingency = $contingencySessionManager->getContingencySession()['dto'];

            if ($contingency instanceof ContingencyMotTestDto) {
                $apiUrl->queryParam('contingencyDate', $contingency->getPerformedAt() . 'T00:00:00Z');
            }
        }
        $apiResult = $this->getRestClient()->get($apiUrl);

        if (!empty($apiResult['data']['checkResult'])) {
            return $apiResult['data']['checkResult'];
        }

        return null;
    }

    /**
     * @return array
     */
    protected function getStaticData()
    {
        /** @var CatalogService $catalogService */
        $catalogService = $this->getCatalogService();

        return [
            'colours'   => new ColoursContainer($catalogService->getColours()),
            'fuelTypes' => $catalogService->getFuelTypes(),
            'emptyVrmReasons' => $catalogService->getReasonsForEmptyVRM(),
            'emptyVinReasons' => $catalogService->getReasonsForEmptyVIN(),
        ];
    }

    protected function checkEligibilityForRetest()
    {
        $data = [];

        $ctSessionMng = $this->getContingencySessionManager();
        if ($ctSessionMng->isMotContingency()) {
            $contingencySession = $ctSessionMng->getContingencySession();

            $data += [
                'contingencyDto' => DtoHydrator::dtoToJson($contingencySession['dto']),
            ];
        }

        try {
            $apiUrl    = VehicleUrlBuilder::retestEligibilityCheck($this->vehicleId, $this->vtsId);
            $apiResult = $this->getRestClient()->post($apiUrl, $data);

            $this->isEligibleForRetest = ($apiResult['data']['isEligible'] === true);
        } catch (ValidationException $e) {
            $this->isEligibleForRetest = false;
            $this->eligibilityNotices  = $e->getDisplayMessages();
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }
    }

    /**
     * @throws \LogicException
     *
     * @return bool
     */
    protected function isRetest()
    {
        if (!isset($this->method)) {
            throw new \LogicException("Method should be set first");
        }

        return $this->method === MotTestTypeCode::RE_TEST;
    }

    /**
     * @return RefuseToTestAssertion
     */
    protected function createRefuseToTestAssertion()
    {
        return new RefuseToTestAssertion($this->getAuthorizationService());
    }

    /**
     * @return ContingencySessionManager
     */
    protected function getContingencySessionManager()
    {
        return $this->serviceLocator->get(ContingencySessionManager::class);
    }
}
