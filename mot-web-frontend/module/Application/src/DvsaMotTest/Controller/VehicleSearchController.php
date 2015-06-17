<?php

namespace DvsaMotTest\Controller;

use Application\Service\CatalogService;
use Application\Service\ContingencySessionManager;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\Vehicle\History\VehicleHistoryDto;
use DvsaCommon\Dto\Vehicle\History\VehicleHistoryMapper;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\UrlBuilder\MotTestUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleUrlBuilder;
use DvsaCommon\UrlBuilder\VehicleUrlBuilderWeb;
use DvsaCommon\Utility\AddressUtils;
use DvsaCommon\Utility\ArrayUtils;
use DvsaMotTest\Constants\VehicleSearchSource;
use DvsaMotTest\Form\VehicleRetestSearch;
use DvsaMotTest\Form\VehicleSearch;
use DvsaMotTest\Model\VehicleSearchResult;
use DvsaMotTest\Service\VehicleSearchService;
use DvsaMotTest\View\VehicleSearchResult\CertificateUrlTemplate;
use DvsaMotTest\View\VehicleSearchResult\DemoTestUrlTemplate;
use DvsaMotTest\View\VehicleSearchResult\MotTestUrlTemplate;
use DvsaMotTest\View\VehicleSearchResult\NoVehiclesFoundMessage;
use DvsaMotTest\View\VehicleSearchResult\VehicleSearchResultMessage;
use DvsaMotTest\View\VehicleSearchResult\VehicleSearchResultUrlTemplateInterface;
use Vehicle\Traits\VehicleServicesTrait;
use Zend\Form\Element\Hidden;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\I18n\Filter\Alnum;
use Zend\View\Model\ViewModel;

/**
 * @internal
 * Currently there are 2 layouts integrated with this.  One for Search and one for Re-test search.
 * If you view both of these interfaces they are completely different.
 * Logic has been seperated, the new layout integrates the VehicleSearchService.
 * As the logic changes for re-test and testhistory, we should integrate the VehicleSearchServie.
 * This will decrease the amount of logic in this controller.
 *
 * Class VehicleSearchController.
 */
class VehicleSearchController extends AbstractDvsaMotTestController
{
    use VehicleServicesTrait;

    const CONTINGENCY_FORM_NOT_RECORDED = 'The contingency test form has not been filled in.';
    const PARTIAL_VIN_NO_REG_ERROR = 'Please complete the registration number field.';
    const NO_VIN_AND_NO_REG_ERROR = 'You must enter the registration mark and VIN to search for a vehicle.';
    const VIN_REQUIRED_ERROR = 'You must enter a VIN to perform the search';
    const MOT_TEST_NUMBER_PARAM = 'motTestNumberOriginal';

    const SEARCH_RESULT_NO_MATCH = 'NO-MATCH';
    const SEARCH_RESULT_EXACT_MATCH = 'EXACT-MATCH';
    const SEARCH_RESULT_MULTIPLE_MATCHES = 'MULTIPLE-MATCHES';
    const SEARCH_RESULT_TOO_MANY_MATCHES = 'TOO-MANY-MATCHES';
    const SEARCH_REGISTRATION_NUMBER_MAX_LENGTH = 13;
    const SEARCH_VIN_NUMBER_MAX_LENGTH = 20;

    // If the VIN length is this long, then a partial match will be used (based on the N last characters)
    const PARTIAL_MATCH_VIN_LENGTH = 6;

    const SEARCH_TYPE = 'searchType';
    const VIN_SEARCH_TYPE = 'vinSearchType';
    const FULL_VIN = 'fullVin';
    const PARTIAL_VIN = 'partialVin';
    const NO_VIN = 'noVin';

    const SEARCH_TYPE_STANDARD = 'standard';
    const SEARCH_TYPE_RETEST = 'retest';
    const SEARCH_TYPE_CERTIFICATE = 'certificate';
    const SEARCH_TYPE_DEMO = 'demo';
    const SEARCH_TYPE_V5C = 'v5c';

    const SEARCH_PARAM_MOT_ID = 'id';
    const SEARCH_PARAM_SEARCH_CRITERIA = 'criteria';
    const SEARCH_PARAM_SEARCH_TYPE = 'type';

    const ROUTE_REPLACEMENT_CERTIFICATE_VEHICLE_SEARCH = 'replacement-certificate-vehicle-search';
    const ROUTE_VEHICLE_SEARCH = 'vehicle-search';
    const ROUTE_VEHICLE_SEARCH_RETEST = 'retest-vehicle-search';
    const ROUTE_VEHICLE_SEARCH_DEMO = 'demo-vehicle-search';

    const PRM_SUBMIT = 'submit';
    const PRM_VIN = 'vin';
    const PRM_REG = 'registration';
    const PRM_VIN_TYPE = 'vinType';
    const PRM_TEST_NR = 'testNumber';

    /**
     * @var array
     */
    protected static $VIN_SEARCH_TYPES
        = [
            self::FULL_VIN => 'Full vin',
            self::PARTIAL_VIN => 'Partial vin',
            self::NO_VIN => 'No vin',
        ];

    /**
     * @var VehicleSearchService
     */
    protected $vehicleSearchService;

    /**
     *
     * @var \DvsaCommon\Obfuscate\ParamObfuscator
     */
    protected $paramObfuscator;

    /**
     * @var CatalogService
     */
    protected $catalogService;

    /**
     * @var VehicleSearchResult
     */
    protected $vehicleSearchResultModel;

    /**
     * @param VehicleSearchService $vehicleSearchService
     * @param ParamObfuscator $paramObfuscator
     * @param CatalogService $catalogService
     * @param VehicleSearchResult $vehicleSearchResultModel
     */
    public function __construct(
        VehicleSearchService $vehicleSearchService,
        ParamObfuscator $paramObfuscator,
        CatalogService $catalogService,
        VehicleSearchResult $vehicleSearchResultModel
    ) {
        $this->paramObfuscator = $paramObfuscator;
        $this->catalogService = $catalogService;
        $this->vehicleSearchResultModel = $vehicleSearchResultModel;
        $this->vehicleSearchService = $vehicleSearchService;
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function retestVehicleSearchAction()
    {
        $this->assertGranted(PermissionInSystem::MOT_TEST_START);
        return $this->vehicleRetestSearch(self::SEARCH_TYPE_RETEST);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function vehicleSearchAction()
    {
        $this->assertGranted(PermissionInSystem::MOT_TEST_START);
        return $this->vehicleSearch(self::SEARCH_TYPE_STANDARD);
    }

    /**
     * Duplicate and Replacement vehicle search.s.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function replacementCertificateVehicleSearchAction()
    {
        $this->assertGranted(PermissionInSystem::CERTIFICATE_READ);

        if (!$this->getAuthorizationService()->isGranted(PermissionInSystem::CERTIFICATE_READ_FROM_ANY_SITE)
            && !$this->getIdentity()->getCurrentVts()
        ) {
            return $this->redirectToLocationSelectScreen();
        }

        return $this->vehicleSearch(self::SEARCH_TYPE_CERTIFICATE);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function demoVehicleSearchAction()
    {
        return $this->vehicleSearch(self::SEARCH_TYPE_DEMO);
    }

    /**
     * History for DVSA users.
     *
     * @return ViewModel
     */
    public function dvsaTestHistoryAction()
    {
        $this->getAuthorizationService()->assertGranted(PermissionInSystem::CERTIFICATE_READ);

        $obfuscatedVehicleId = (string)$this->params('id', 0);
        $vehicleId = $this->paramObfuscator->deobfuscateEntry(
            ParamObfuscator::ENTRY_VEHICLE_ID,
            $obfuscatedVehicleId,
            false
        );
        $vin = $this->params()->fromQuery('vin');
        $registration = $this->params()->fromQuery('registration');

        $vehicleHistory = new VehicleHistoryDto();
        $vehicle = [];

        try {
            // Get vehicle data.
            $vehicle = $this->getMapperFactory()->Vehicle->getById($vehicleId);

            // Get history data.
            $apiUrl = VehicleUrlBuilder::testHistory($vehicleId);
            $apiResult = $this->getRestClient()->get($apiUrl);

            $vehicleHistory = (new VehicleHistoryMapper())->fromArrayToDto($apiResult['data'], 0);
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }

        return new ViewModel(
            [
                'vehicleHistory' => $vehicleHistory,
                'vehicle' => $vehicle,
                'vin' => $vin,
                'registration' => $registration
            ]
        );
    }

    /**
     * @param string $obfuscatedVehicleId
     * @param array $postParams
     *
     * @return \Zend\Http\Response
     */
    protected function handleDifferentVtsAction($obfuscatedVehicleId, $postParams)
    {
        $motTestId = ArrayUtils::get($postParams, 'id');
        $motTestNumber = ArrayUtils::get($postParams, 'number');
        $v5c = ArrayUtils::get($postParams, 'v5c');

        if (($v5c && $motTestNumber) || (!$v5c && !$motTestNumber)) {
            $this->addErrorMessages('Please enter either V5C number or MOT certificate number');

            return $this->redirect()->toUrl(VehicleUrlBuilderWeb::historyMotCertificates($obfuscatedVehicleId));
        }

        if ($motTestId) {
            $apiUrl = $searchCriteria = $searchType = null;

            if ($v5c) {
                $apiUrl = MotTestUrlBuilder::findByMotTestIdAndV5c($motTestId, $v5c)->toString();
                $searchCriteria = $v5c;
                $searchType = self::SEARCH_TYPE_V5C;
            } elseif ($motTestNumber) {
                $apiUrl = MotTestUrlBuilder::findByMotTestIdAndMotTestNumber($motTestId, $motTestNumber)->toString();
                $searchCriteria = $motTestNumber;
                $searchType = self::SEARCH_TYPE_CERTIFICATE;
            }

            return $this->handleV5cOrMotTestNumberCall(
                $obfuscatedVehicleId,
                $apiUrl,
                $searchCriteria,
                $searchType,
                $motTestId
            );
        }

        return $this->redirect()->toUrl(VehicleUrlBuilderWeb::historyMotCertificates($obfuscatedVehicleId));
    }

    /**
     * @param string $obfuscatedVehicleId
     * @param string $apiUrl
     * @param string $searchCriteria
     * @param string $searchType
     * @param string $motTestId
     *
     * @return \Zend\Http\Response
     */
    protected function handleV5cOrMotTestNumberCall(
        $obfuscatedVehicleId,
        $apiUrl,
        $searchCriteria,
        $searchType,
        $motTestId
    ) {
        $apiResult = $this->getRestClient()->get($apiUrl);

        $motTestNumberFromApi = $apiResult['data'];

        if ($motTestNumberFromApi) {
            return $this->redirect()->toRoute(
                'mot-test-certificate',
                ['motTestNumber' => $motTestNumberFromApi]
            );
        }

        if ($searchType === self::SEARCH_TYPE_V5C) {
            $this->addErrorMessages('The V5C number is incorrect');
        } else {
            $this->addErrorMessages('The MOT certificate number is incorrect');
        }

        return $this->redirect()->toRoute(
            'vehicle-test-history',
            ['id' => $obfuscatedVehicleId],
            [
                'query' => [
                    self::SEARCH_PARAM_SEARCH_CRITERIA => urlencode($searchCriteria),
                    self::SEARCH_PARAM_SEARCH_TYPE => urlencode($searchType),
                    self::SEARCH_PARAM_MOT_ID => urlencode($motTestId),
                ],
                'fragment' => 'show-form-' . $motTestId,
            ]
        );
    }

    /**
     * History for testers.
     *
     * @throws \DvsaCommon\Obfuscate\InvalidArgumentException
     *
     * @return ViewModel
     */
    public function testHistoryAction()
    {
        $this->getAuthorizationService()->assertGranted(PermissionInSystem::CERTIFICATE_READ);
        $obfuscatedVehicleId = (string)$this->params()->fromRoute('id', 0);
        $vehicleId = $this->paramObfuscator->deobfuscateEntry(
            ParamObfuscator::ENTRY_VEHICLE_ID,
            $obfuscatedVehicleId,
            false
        );
        $motId = (int)$this->params()->fromQuery('id', 0);
        $searchType = $this->params()->fromQuery('type', self::SEARCH_TYPE_V5C);
        $searchCriteria = urldecode($this->params()->fromQuery('criteria', ''));
        $vin = $this->params()->fromQuery('vin');
        $registration = $this->params()->fromQuery('registration');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            return $this->handleDifferentVtsAction($obfuscatedVehicleId, $request->getPost()->toArray());
        }

        /** @var \DvsaAuthentication\Model\VehicleTestingStation $site */
        $site = $this->getIdentity()->getCurrentVts();
        $siteId = $site->getVtsId();

        $vehicleHistory = new VehicleHistoryDto();
        $vehicle = [];

        try {
            // Get vehicle data.
            $vehicle = $this->getMapperFactory()->Vehicle->getById($vehicleId);

            // Get history data.
            $apiUrl = VehicleUrlBuilder::testHistory($vehicleId);
            $apiResult = $this->getRestClient()->get($apiUrl);

            $vehicleHistory = (new VehicleHistoryMapper())->fromArrayToDto($apiResult['data'], $siteId);
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }

        return new ViewModel(
            [
                'obfuscatedVehicleId' => $obfuscatedVehicleId,
                'vehicleHistory' => $vehicleHistory,
                'vehicle' => $vehicle,
                'siteName' => $site->getName() . ', ' . AddressUtils::stringify($site->getAddress()),
                'searchParamMotId' => $motId,
                'searchParamType' => $searchType,
                'searchParamCriteria' => $searchCriteria,
                'registration' => $registration,
                'vin' => $vin
            ]
        );
    }


    protected function vehicleRetestSearch($searchType = null)
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        /*
         * We check if we the user start to test a contingency test and validate if the form is really set in the
         * session. If not we redirect to an error page who ask the user to fill the form.
         */
        $ctSessionMng = $this->getContingencySessionManager();

        $isContingency = ($request->getQuery('contingency') !== null);
        if ($isContingency) {
            if ($ctSessionMng->isMotContingency() === false) {
                $ctSessionMng->deleteContingencySession();

                return $this->redirect()->toRoute('contingency-error');
            }
        } else {
            $ctSessionMng->deleteContingencySession();
        }

        if ($searchType !== self::SEARCH_TYPE_DEMO
            && $this->getAuthorizationService()->isTester()
            && !$this->getIdentity()->getCurrentVts()
        ) {
            $loggedInUserManager = $this->getServiceLocator()->get('LoggedInUserManager');
            $tester = $loggedInUserManager->getTesterData();
            // Avoid redirecting to the LocationSelectionController if the tester has only one associated site.
            if (count($tester['vtsSites']) == 1) {
                $loggedInUserManager->changeCurrentLocation($tester['vtsSites'][0]['id']);
            } else {
                $routeMatch = $this->getRouteMatch();
                $route = $routeMatch->getMatchedRouteName();
                $params = [];
                if ($isContingency) {
                    $params = ['contingency' => 1];
                }

                $container = $this->getServiceLocator()->get('LocationSelectContainerHelper');
                $container->persistConfig(['route' => $route, 'params' => $params]);

                return $this->redirect()->toRoute('location-select');
            }
        }

        if ($this->getVehicleSearchService()->areSlotsNeeded($searchType)) {
            $slots = $this->getIdentity()->getCurrentVts()->getSlots();
            if (!$slots) {
                $this->addErrorMessages('Your VTS has no slots available');

                return $this->redirect()->toUrl(PersonUrlBuilderWeb::home());
            }
        }

        $showCreateButton = false;
        $form = $this->getForm(new VehicleRetestSearch());
        $query = $request->getPost();

        if ($ctSessionMng->isMotContingency() === true) {
            $hiddenContingencyField = new Hidden('contingency');
            $hiddenContingencyField->setValue('1');
            $form->add($hiddenContingencyField);
        }

        if ($query->get(self::PRM_SUBMIT)) {
            $form->setData($query);

            if ($form->isValid()) {
                // indicates whether we should search for vehicles in DVLA data source or not
                $excludeDvla = !$this->getVehicleSearchService()->shouldSearchInDvlaVehicleList($searchType);

                $data = [
                    'vin' => $query->get(self::PRM_VIN),
                    'reg' => $query->get(self::PRM_REG),
                    'testNumber' => $query->get(self::PRM_TEST_NR),
                    'vinType' => $query->get(self::PRM_VIN_TYPE),
                    'excludeDvla' => $excludeDvla ? 'true' : 'false', // todo refactor api to use bool
                ];

                $noRegistration = ($data['reg'] || $data['testNumber']) ? 0 : 1;

                if (isset($data['testNumber'])) {
                    $motTestNumber = $data['testNumber'];
                    $vehicle = $this->getVehicleSearchService()->getVehicleFromMotTestCertificate($motTestNumber);

                    if (!$vehicle) {
                        $errMsg = "MOT test $motTestNumber not found";
                        $this->addErrorMessages($errMsg);
                    }

                    if ($vehicle instanceof VehicleDto) {
                        $vehicle->setId(
                            $this->paramObfuscator->obfuscateEntry(
                                ParamObfuscator::ENTRY_VEHICLE_ID,
                                $vehicle->getId()
                            )
                        );

                        return $this->returnRedirectStartRetestConfirmation($vehicle->getId(), $noRegistration);
                    }

                    return $this->returnViewModel($form, $searchType, false, false);
                }

                if ($data['vinType'] == self::PARTIAL_VIN && !$data['reg']) {
                    $this->addErrorMessages(self::PARTIAL_VIN_NO_REG_ERROR);

                    return $this->returnViewModel($form, $searchType, false, true);
                }

                // Check that the registration number doesn't exceed `self::SEARCH_REGISTRATION_NUMBER_MAX_LENGTH`
                // characters.
                if (strlen($data['reg']) > self::SEARCH_REGISTRATION_NUMBER_MAX_LENGTH) {
                    $this->addErrorMessages(
                        sprintf(
                            "The registration number must not exceed %d characters.",
                            self::SEARCH_REGISTRATION_NUMBER_MAX_LENGTH
                        )
                    );

                    return $this->returnViewModel($form, $searchType, false, true);
                }

                $form->get('previousSearchRegistration')->setValue($data['reg']);
                $form->get('previousSearchVin')->setValue($data['vin']);
                $form->get('registration')->setValue($data['reg']);
                $form->get('vin')->setValue($data['vin']);

                try {
                    $apiUrl = VehicleUrlBuilder::vehicle();
                    $result = $this->getRestClient()->getWithParams($apiUrl, $data);

                    $resultType = $result['data']['resultType'];

                    switch ($resultType) {
                        case self::SEARCH_RESULT_EXACT_MATCH:
                            $vehicleId = $this->paramObfuscator->obfuscateEntry(
                                ParamObfuscator::ENTRY_VEHICLE_ID,
                                $result['data']['vehicle']['id']
                            );
                            $isDvla = $result['data']['vehicle']['isDvla'];

                            return $this->redirectTo($searchType, $vehicleId, $noRegistration, $isDvla);
                        case self::SEARCH_RESULT_MULTIPLE_MATCHES:
                            $results = $this->getVehicleSearchService()->obfuscateIdAndAddSourceToVehicleArray(
                                $result['data']['vehicles']
                            );
                            return $this->returnViewModel($form, $searchType, false, false, false, $results, false);
                        case self::SEARCH_RESULT_TOO_MANY_MATCHES:
                            return $this->returnViewModel($form, $searchType, false, false, false, null, true);
                        default:
                            $showCreateButton = $searchType == self::SEARCH_TYPE_STANDARD
                                && strtoupper($query->get('previousSearchRegistration')) == strtoupper($data['reg'])
                                && strtoupper($query->get('previousSearchVin')) == strtoupper($data['vin']);
                    }
                } catch (RestApplicationException $e) {
                    $this->addErrorMessages($e->getDisplayMessages());

                    return $this->returnViewModel($form, $searchType, false, false, $showCreateButton);
                }

                return $this->returnViewModel($form, $searchType, true, false, $showCreateButton);
            }
        }

        return $this->returnViewModel($form, $searchType);
    }

    /**
     * @param null $searchType
     *
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    protected function vehicleSearch($searchType = null)
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        /*
         * We check if we the user start to test a contingency test and validate if the form is really set in the
         * session. If not we redirect to an error page who ask the user to fill the form.
         */
        $ctSessionMng = $this->getContingencySessionManager();

        $isContingency = ($request->getQuery('contingency') !== null);
        if ($isContingency) {
            if ($ctSessionMng->isMotContingency() === false) {
                $ctSessionMng->deleteContingencySession();

                return $this->redirect()->toRoute('contingency-error');
            }
        } else {
            $ctSessionMng->deleteContingencySession();
        }

        if ($searchType !== self::SEARCH_TYPE_DEMO
            && $this->getAuthorizationService()->isTester()
            && !$this->getIdentity()->getCurrentVts()
        ) {
            $loggedInUserManager = $this->getServiceLocator()->get('LoggedInUserManager');
            $tester = $loggedInUserManager->getTesterData();
            // Avoid redirecting to the LocationSelectionController if the tester has only one associated site.
            if (count($tester['vtsSites']) == 1) {
                $loggedInUserManager->changeCurrentLocation($tester['vtsSites'][0]['id']);
            } else {
                $routeMatch = $this->getRouteMatch();
                $route = $routeMatch->getMatchedRouteName();
                $params = [];
                if ($isContingency) {
                    $params = ['contingency' => 1];
                }

                $container = $this->getServiceLocator()->get('LocationSelectContainerHelper');
                $container->persistConfig(['route' => $route, 'params' => $params]);

                return $this->redirect()->toRoute('location-select');
            }
        }

        if ($this->getVehicleSearchService()->areSlotsNeeded($searchType)) {
            $slots = $this->getIdentity()->getCurrentVts()->getSlots();
            if (!$slots) {
                $this->addErrorMessages('Your VTS has no slots available');

                return $this->redirect()->toUrl(PersonUrlBuilderWeb::home());
            }
        }

        $showCreateButton = false;
        $form = $this->getForm(new VehicleSearch());

        if ($ctSessionMng->isMotContingency() === true) {
            $hiddenContingencyField = new Hidden('contingency');
            $hiddenContingencyField->setValue('1');
            $form->add($hiddenContingencyField);
        }

        $reg = $this->params()->fromQuery(self::PRM_REG, null);
        $vin = $this->params()->fromQuery(self::PRM_VIN, null);

        if (!is_null($reg) && !is_null($vin)) {
            $filter = new Alnum();
            $data = [
                  self::PRM_VIN => strtoupper($vin),
                  self::PRM_REG => strtoupper($reg),
            ];
            $form->setData($data);

            if ($form->isValid()) {
                $noRegistration = ($data[self::PRM_REG]) ? 0 : 1;

                if (!($data[self::PRM_REG] || $data[self::PRM_VIN])) {
                    return $this->returnViewModel(
                        $form,
                        $searchType,
                        true,
                        true,
                        $searchType == self::SEARCH_TYPE_STANDARD,
                        [],
                        false,
                        null,
                        $this->getVehicleSearchService()->getSearchResultMessage(
                            $data[self::PRM_REG],
                            $data[self::PRM_VIN],
                            0
                        )
                    );
                }

                try {
                    $vehicles = $this->getVehicleSearchService()->search(
                        $data[self::PRM_VIN],
                        $data[self::PRM_REG],
                        !$this->getVehicleSearchService()->shouldSearchInDvlaVehicleList($searchType)
                    );

                    return $this->returnViewModel(
                        $form,
                        $searchType,
                        empty($vehicles),
                        false,
                        $searchType == self::SEARCH_TYPE_STANDARD,
                        $vehicles,
                        false,
                        $this->getUrlTemplate($searchType, $noRegistration),
                        $this->getVehicleSearchService()->getSearchResultMessage(
                            $data[self::PRM_REG],
                            $data[self::PRM_VIN],
                            count($vehicles)
                        )
                    );
                } catch (RestApplicationException $e) {
                    $this->addErrorMessages($e->getDisplayMessages());

                    return $this->returnViewModel($form, $searchType, false, false, $showCreateButton);
                }
            }
        }

        return $this->returnViewModel($form, $searchType);
    }

    /**
     * @param $searchType
     * @param $noRegistration
     * @return CertificateUrlTemplate|DemoTestUrlTemplate|MotTestUrlTemplate
     * @throws \Exception
     */
    private function getUrlTemplate($searchType, $noRegistration)
    {
        $urlPlugin = $this->url();
        switch ($searchType) {
            case self::SEARCH_TYPE_CERTIFICATE:
                return new CertificateUrlTemplate($this->getAuthorizationService(), $urlPlugin);
            case self::SEARCH_TYPE_DEMO:
                return new DemoTestUrlTemplate($noRegistration, $urlPlugin);
            case self::SEARCH_TYPE_STANDARD:
                return new MotTestUrlTemplate($noRegistration, $urlPlugin);
        }

        throw new \Exception("Unknown search type");
    }

    /**
     * @param \Zend\Form\Form $form form object for vehicle search
     * @param string $searchType one of search types
     * @param bool $noMatches true if there were no matches in previous search
     * @param bool $regError true if reg was not provided, but required
     * @param bool $showCreateButton true to show Create Vehicle button
     * @param array $results show any non exact or multiple results for search criteria
     * @param bool $tooManyMatches more than five results returned
     * @param VehicleSearchResultUrlTemplateInterface $urlTemplate creates a searchType specific url to follow from results list
     * @param VehicleSearchResultMessage $searchResultMessage message for the user when no results are found
     *
     *
     * @return ViewModel vehicle search view model
     */
    protected function returnViewModel(
        $form,
        $searchType,
        $noMatches = false,
        $regError = false,
        $showCreateButton = false,
        $results = null,
        $tooManyMatches = false,
        VehicleSearchResultUrlTemplateInterface $urlTemplate = null,
        VehicleSearchResultMessage $searchResultMessage = null
    ) {
        $contingencySession = $this->getContingencySessionManager();

        $viewModel = new ViewModel(
            [
                'form' => $form,
                'regError' => $regError,
                'noMatches' => $noMatches,
                'showCreateButton' => $showCreateButton,
                'results' => $results,
                'tooManyMatches' => $tooManyMatches,
                'isMotContingency' => $contingencySession->isMotContingency(),
                'registrationNumberMaxLength' => self::SEARCH_REGISTRATION_NUMBER_MAX_LENGTH,
                'searchResultMessage' => $searchResultMessage,
                self::SEARCH_TYPE => $searchType,
                self::VIN_SEARCH_TYPE => self::$VIN_SEARCH_TYPES,
                'urlTemplate' => $urlTemplate,
            ]
        );

        $viewModel->setTemplate('dvsa-mot-test/vehicle-search/vehicle-search.phtml');

        return $viewModel;
    }

    /**
     * @param $searchType
     * @param $vehicleId
     * @param $noRegistration
     * @param $isDvla
     *
     * @return \Zend\Http\Response
     */
    protected function redirectTo($searchType, $vehicleId, $noRegistration, $isDvla)
    {
        switch ($searchType) {
            case self::SEARCH_TYPE_RETEST:
                return $this->returnRedirectStartRetestConfirmation($vehicleId, $noRegistration);
            case self::SEARCH_TYPE_CERTIFICATE:
                return $this->returnRedirectTestHistory($vehicleId);
            case self::SEARCH_TYPE_DEMO:
                return $this->returnRedirectStartDemoConfirmation($vehicleId, $noRegistration);
            default:
                return $this->redirectToConfirmation($vehicleId, $noRegistration, $isDvla);
        }
    }

    /**
     * @param $id
     * @param $noRegistration
     * @param $isDvla
     *
     * @return \Zend\Http\Response
     */
    protected function redirectToConfirmation($id, $noRegistration, $isDvla)
    {
        return $this->redirect()->toRoute(
            'start-test-confirmation',
            [
                'controller' => 'StartTestConfirmation',
                'action' => 'index',
                StartTestConfirmationController::ROUTE_PARAM_ID => $id,
                StartTestConfirmationController::ROUTE_PARAM_NO_REG => $noRegistration,
                StartTestConfirmationController::ROUTE_PARAM_SOURCE => ($isDvla
                    ? VehicleSearchSource::DVLA
                    : VehicleSearchSource::VTR
                ),
            ]
        );
    }

    /**
     * @param $id
     * @param $noRegistration
     *
     * @return \Zend\Http\Response
     */
    protected function returnRedirectStartRetestConfirmation($id, $noRegistration)
    {
        return $this->redirect()->toRoute(
            'start-retest-confirmation',
            [
                'controller' => 'StartTestConfirmation',
                'action' => 'index',
                StartTestConfirmationController::ROUTE_PARAM_ID => $id,
                StartTestConfirmationController::ROUTE_PARAM_NO_REG => $noRegistration,
            ]
        );
    }

    /**
     * @param $id
     * @param $noRegistration
     *
     * @return \Zend\Http\Response
     */
    protected function returnRedirectStartDemoConfirmation($id, $noRegistration)
    {
        return $this->redirect()->toRoute(
            'start-demo-confirmation',
            [
                StartTestConfirmationController::ROUTE_PARAM_ID => $id,
                StartTestConfirmationController::ROUTE_PARAM_NO_REG => $noRegistration,
            ]
        );
    }

    /**
     * @param $obfuscatedVehicleId
     *
     * @return \Zend\Http\Response
     */
    protected function returnRedirectTestHistory($obfuscatedVehicleId)
    {
        $isDvsaUser = $this->getAuthorizationService()->isGranted(PermissionInSystem::CERTIFICATE_READ_FROM_ANY_SITE);

        if ($isDvsaUser) {
            $url = VehicleUrlBuilderWeb::historyDvlaMotCertificates($obfuscatedVehicleId);
        } else {
            $url = VehicleUrlBuilderWeb::historyMotCertificates($obfuscatedVehicleId);
        }

        return $this->redirect()->toUrl($url);
    }

    /**
     * @return \Zend\Http\Response
     */
    protected function redirectToLocationSelectScreen()
    {
        $routeMatch = $this->getRouteMatch();
        $container = $this->getServiceLocator()->get('LocationSelectContainerHelper');
        $container->persistConfig(
            [
                'route' => $routeMatch->getMatchedRouteName(),
                'params' => $routeMatch->getParams(),
            ]
        );

        return $this->redirect()->toRoute(LocationSelectController::ROUTE);
    }

    /**
     * @return ContingencySessionManager
     */
    protected function getContingencySessionManager()
    {
        return $this->serviceLocator->get(ContingencySessionManager::class);
    }

    /**
     * @return VehicleSearchService
     */
    private function getVehicleSearchService()
    {
        return $this->vehicleSearchService;
    }

    /**
     * @return \Zend\Mvc\Router\RouteMatch
     */
    private function getRouteMatch()
    {
        return $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();
    }
}
