<?php

namespace Vehicle\Controller;

use Core\Controller\AbstractAuthActionController;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleUrlBuilderWeb;
use Vehicle\Service\VehicleSearchService;
use Zend\Stdlib\Parameters;
use Zend\Stdlib\ParametersInterface;
use Zend\View\Model\ViewModel;

/**
 * Class VehicleController.
 *
 * This class is responsible for coordinating the different search methods to user so they can quickly locate an
 * existing VTS station, vehicle or other entity.
 */
class VehicleController extends AbstractAuthActionController
{
    const BACK_TO_DETAIL             = 'detail';
    const BACK_TO_RESULT             = 'result';
    const BACK_TO_SEARCH             = 'search';
    const ERR_MSG_INVALID_VEHICLE_ID = 'No Vehicle Id provided';
    const FORM_ERROR                 = 'Unable to find Vehicle';
    const NO_RESULT_FOUND            = 'Search term(s) not found...';

    /**
     * @var MapperFactory
     */
    protected $mapperFactory;

    /**
     * @var \DvsaCommon\Obfuscate\ParamObfuscator
     */
    protected $paramObfuscator;

    /**
     * @param \DvsaCommon\Obfuscate\ParamObfuscator $paramObfuscator
     */
    public function __construct(ParamObfuscator $paramObfuscator, MapperFactory $mapperFactory)
    {
        $this->paramObfuscator = $paramObfuscator;
        $this->mapperFactory = $mapperFactory;
    }

    /**
     * Show vehicle details.
     *
     * @throws \Exception
     * @throws \DvsaCommon\Auth\NotLoggedInException
     * @throws \DvsaCommon\Obfuscate\InvalidArgumentException
     *
     * @return array|\Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        if (!$this->getAuthorizationService()->isGranted(PermissionInSystem::FULL_VEHICLE_MOT_TEST_HISTORY_VIEW)) {
            return $this->redirect()->toUrl(PersonUrlBuilderWeb::home());
        }

        $obfuscatedVehicleId = (string) $this->params('id');
        $vehicleId = $this->paramObfuscator->deobfuscateEntry(
            ParamObfuscator::ENTRY_VEHICLE_ID, $obfuscatedVehicleId, false
        );
        if ((int) $vehicleId == 0) {
            throw new \Exception(self::ERR_MSG_INVALID_VEHICLE_ID);
        }

        $vehicle = null;
        try {
            $vehicle = $this->mapperFactory->Vehicle->getById($vehicleId);
        } catch (ValidationException $e) {
            $this->addErrorMessages(self::FORM_ERROR);
        }

        $searchData = $this->getRequest()->getQuery();

        //  --  view model  --
        return new ViewModel(
            [
                'vehicle' => $vehicle,
                'urls'    => [
                    'back'    => $this->getUrlToBack($searchData),
                    'history' => $this->getUrlToHistory($obfuscatedVehicleId, $searchData),
                ],
            ]
        );
    }

    /**
     * @param \Zend\Stdlib\ParametersInterface $searchData
     *
     * @return string
     */
    protected function getUrlToBack(ParametersInterface $searchData)
    {
        if ($searchData->count()) {
            $backTo = $searchData->get('backTo');

            $searchData->set('backTo', null);

            if ($backTo == self::BACK_TO_SEARCH) {
                $url = VehicleUrlBuilderWeb::search();
            } else {
                $url = VehicleUrlBuilderWeb::searchResult();
            }

            return $url->toString() . '?' . htmlspecialchars(http_build_query($searchData));
        }

        return VehicleUrlBuilderWeb::search()->toString();
    }

    /**
     * @param obfuscatedVehicleId
     * @param \Zend\Stdlib\Parameters $searchData
     *
     * @return string
     */
    protected function getUrlToHistory($obfuscatedVehicleId, Parameters $searchData)
    {
        $searchData->set('backTo', VehicleController::BACK_TO_DETAIL);

        return VehicleUrlBuilderWeb::historyMotTests($obfuscatedVehicleId)->toString() . '?' .
            htmlspecialchars(
                http_build_query(
                    $searchData->toArray()
                )
            );
    }

    /**
     * This action is responsible for showing the search screen for a vehicle and handle the post of the form.
     *
     * @throws \DvsaCommon\Auth\NotLoggedInException
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function searchAction()
    {
        $this->layout('layout/layout_enforcement');

        if (!$this->getAuthorizationService()->isGranted(PermissionInSystem::VEHICLE_READ)) {
            return $this->redirect()->toUrl(PersonUrlBuilderWeb::home());
        }

        /** @var \Zend\Http\Request $request */
        $request      = $this->getRequest();
        $searchParams = $request->isPost() ? $request->getPost() : $request->getQuery();

        return new ViewModel(
            [
                'searchParams' => $searchParams,
            ]
        );
    }

    /**
     * This action is responsible for handling the search and showing the results/page details of the vehicle in
     * function of the number of results.
     * If there no results found the action redirect to search page with an error message.
     *
     * @throws \DvsaCommon\Auth\NotLoggedInException
     *
     * @return ViewModel
     */
    public function resultAction()
    {
        $this->layout('layout/layout_enforcement');

        if (!$this->getAuthorizationService()->isGranted(PermissionInSystem::VEHICLE_READ)) {
            return $this->redirect()->toUrl(PersonUrlBuilderWeb::home());
        }

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $form    = $request->isPost() ? $request->getPost() : $request->getQuery();
        if (!isset($form)) {
            return $this->redirect()->toUrl(VehicleUrlBuilderWeb::search());
        }

        $vehicleSearchService = new VehicleSearchService($this, $this->getRestClient(), $form, $this->paramObfuscator);

        try {
            $vehicleSearchService->getVehicleResults();
        } catch (RestApplicationException $e) {
            $this->addErrorMessages(self::NO_RESULT_FOUND);

            return $this->redirect()->toUrl(VehicleUrlBuilderWeb::search());
        }

        return $vehicleSearchService->checkVehicleResults();
    }

    /**
     * Callback function to add an error from the decorator.
     *
     * @param $errors
     */
    public function addErrorMessagesFromService($errors)
    {
        $this->addErrorMessages($errors);
    }
}
