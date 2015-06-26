<?php
namespace DvsaMotTest\Controller;

use Application\Helper\PrgHelper;
use Application\Service\CatalogService;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\OdometerReadingResultType;
use DvsaCommon\Dto\Common\ColourDto;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Dto\Vehicle\CountryDto;
use DvsaCommon\HttpRestJson\Exception\NotFoundException;
use DvsaCommon\HttpRestJson\Exception\OtpApplicationException;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\MotTestUrlBuilder;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\UrlBuilder\UrlBuilderWeb;
use DvsaCommon\Utility\AddressUtils;
use DvsaCommon\Utility\ArrayUtils;
use DvsaMotTest\Model\OdometerReadingViewObject;
use DvsaMotTest\Model\OdometerUpdate;
use DvsaMotTest\View\Model\MotPrintModel;
use DvsaMotTest\View\ReplacementMakeViewModel;
use DvsaMotTest\View\ReplacementSiteViewModel;
use DvsaMotTest\View\ReplacementVehicleViewModel;
use Vehicle\Helper\ColoursContainer;
use Vehicle\Service\VehicleCatalogService;
use Zend\View\Model\ViewModel;

/**
 * Class ReplacementCertificateController
 *
 * @package DvsaMotTest\Controller
 */
class ReplacementCertificateController extends AbstractDvsaMotTestController
{
    const TEMPLATE_REPLACEMENT_CERTIFICATE = 'dvsa-mot-test/mot-test/replacement-certificate';
    const TEMPLATE_REPLACEMENT_CERTIFICATE_FINISH = 'dvsa-mot-test/mot-test/replacement-certificate-finish';
    const TEMPLATE_REPLACEMENT_CERTIFICATE_SUMMARY = 'dvsa-mot-test/mot-test/replacement-certificate-summary';
    const TEMPLATE_REPLACEMENT_CERTIFICATE_OTHER_VEHICLE = 'dvsa-mot-test/mot-test/replacement-certificate-other-vehicle';

    const SITE_NOT_FOUND = 'Site number not found';
    const INTACT_CERT_DETAIL = 'Nothing has changed for the certificate';

    const ACTION_UPDATE_ODOMETER = 'updateOdometer';

    const MAKE_MODEL_OTHER_VALUE = 'other';
    const MODEL_VALUE_EMPTY = 'Model not found';

    /** @var VehicleCatalogService */
    private $vehicleCatalogService;

    /**
     * @param VehicleCatalogService $vehicleCatalogService
     */
    public function __construct(VehicleCatalogService $vehicleCatalogService)
    {
        $this->vehicleCatalogService = $vehicleCatalogService;
    }

    /**
     * @return null|\Zend\Http\Response|ViewModel
     */
    public function replacementCertificateAction()
    {
        $this->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);

        $id = $this->params()->fromRoute('id', null);
        $makeCode = $this->params()->fromRoute('makeCode', null);

        if ($this->getRequest()->isPost()) {
            if ($id === null) {
                return $this->createDraft($this->params()->fromPost('motTestNumber'));
            }

            return $this->updateDraft($id);
        }

        return $this->showDraft($id, $makeCode);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function reviewAction()
    {
        $this->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);

        $id = $this->params()->fromRoute('id', 0);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        //  --  double post check  --
        $prgHelper = new PrgHelper($request);
        if ($prgHelper->isRepeatPost()) {
            return $this->redirect()->toUrl($prgHelper->getRedirectUrl());
        }

        //  --  get draft data from Api --
        $draft = $this->getDraftData($id);
        $motTestNumber = ArrayUtils::tryGet($draft, 'motTestNumber');

        //  --  get mottest data from Api --
        /** @var MotTestDto $motTest */
        $motTest = $this->tryGetMotTestOrAddErrorMessages($motTestNumber);
        $otpErrorData = null;

        if ($request->isPost()) {
            $urlFinish = UrlBuilderWeb::replacementCertificateFinish($motTestNumber);

            $prgHelper->setRedirectUrl($urlFinish->toString());

            try {
                if (!$this->hasAdminRights()) {
                    $testerDto = $motTest->getTester();
                    $isOriginalTester = $this->getIdentity()->getUserId() === $testerDto->getId();
                    if (!$isOriginalTester) {
                        $diffTesterReasonCode = $this->params()->fromPost("reasonForDifferentTester");
                        $data = ['reasonForDifferentTester' => $diffTesterReasonCode];
                        $this->getRestClient()->put(
                            UrlBuilder::replacementCertificateDraft($id),
                            $data
                        );
                    }
                }

                $otp = $this->params()->fromPost("oneTimePassword");
                $data = ['oneTimePassword' => $otp];
                $this->getRestClient()->post(
                    UrlBuilder::replacementCertificateDraftApply($id),
                    $data
                );

                return $this->redirect()->toUrl($urlFinish);
            } catch (OtpApplicationException $e) {
                $errorData = $e->getErrorData();

                if (isset($errorData['attempts']) && $errorData['attempts']['total'] > 0) {
                    if (isset($errorData['message'])) {
                        $message = $errorData['message'];
                        $this->addErrorMessages($message);
                    }
                }

                $otpErrorData = $e->getErrorData();
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }
        }

        /** @var ColourDto $primaryColour */
        $primaryColour = $motTest->getPrimaryColour();
        $primaryColour->setName($draft['primaryColour']['name']);

        $secondaryColourDraft = $draft['secondaryColour'];
        /** @var ColourDto $secondaryColour */
        $secondaryColour = $motTest->getSecondaryColour();

        if (!$secondaryColour) {
            $secondaryColour = new ColourDto();
        }

        $secondaryColour
            ->setName(ArrayUtils::tryGet($secondaryColourDraft, 'name'))
            ->setCode(ArrayUtils::tryGet($secondaryColourDraft, 'code'));

        if ($this->hasAdminRights()) {
            /** @var CountryDto $countryOfRegistration */
            $countryOfRegistration = $motTest->getCountryOfRegistration();

            $motTest->setVin($draft['vin']);
            $motTest->setRegistration($draft['vrm']);
            $motTest->setModel($draft['model']['name']);
            $motTest->setMake($draft['make']['name']);
            $countryOfRegistration->setName($draft['countryOfRegistration']['name']);

            $draft['vts']['address'] = AddressUtils::stringify($draft['vts']['address']);
            $motTest->setVehicleTestingStation($draft['vts']);
        }

        $odometerReadingVO = OdometerReadingViewObject::create()
            ->setOdometerReadingValuesMap($draft['odometerReading']);

        /** @var PersonDto $tester */
        $tester = $motTest->getTester();
        $isOriginalTester = $this->getIdentity()->getUserId() === $tester->getId();

        $differentTesterReasons = [];
        if (!$isOriginalTester) {
            $reasons = $this->getRestClient()->get('cert-change-diff-tester-reason')['data'];

            foreach ($reasons as $r) {
                $differentTesterReasons[$r['code']] = $r['description'];
            }
        }

        $viewModel = new ViewModel(
            [
                'motTest' => $motTest,
                'odometerReading' => $odometerReadingVO,
                'isOriginalTester' => $isOriginalTester,
                'differentTesterReasons' => $differentTesterReasons,
                'draftId' => $id,
                'isAdmin' => $this->hasAdminRights(),
                'canTestWithoutOpt' => $this->canTestWithoutOtp(),
                'otpErrorData' => $otpErrorData,
                'prgHelper' => $prgHelper,
            ]
        );
        $viewModel->setTemplate(self::TEMPLATE_REPLACEMENT_CERTIFICATE_SUMMARY);

        return $viewModel;
    }

    /**
     * @return \Zend\Http\Response
     */
    public function finishAction()
    {
        $this->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);

        $motTestNumber = $this->params("motTestNumber");
        $motTest = $this->tryGetMotTestOrAddErrorMessages($motTestNumber);

        $modelPrintViewModel = new MotPrintModel(
            [
                'motDetails' => $motTest,
                'motTestNumber' => $motTestNumber,
            ]
        );

        $modelPrintViewModel->setTemplate(self::TEMPLATE_REPLACEMENT_CERTIFICATE_FINISH);

        return $modelPrintViewModel;
    }

    public function otherVehicleAction()
    {
        $this->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS);

        $id = $this->params()->fromRoute('id', null);
        $makeCode = $this->params()->fromRoute('makeCode', null);

        $draft = $this->getDraftData($id);

        if ($this->getRequest()->isPost()) {
            if ($id === null) {
                return $this->createDraft($this->params()->fromPost('motTestNumber'));
            }

            return $this->updateDraft($id);
        }

        $viewData = $this->buildViewData($draft, $makeCode);

        $viewData = array_merge(
            $viewData,
            [
                'isAdmin' => $this->hasAdminRights(),
                'isTester' => $this->hasTesterRights(),
                'id' => $id
            ]
        );

        $this->layout('layout/layout-govuk.phtml');

        return (new ViewModel($viewData))->setTemplate(self::TEMPLATE_REPLACEMENT_CERTIFICATE_OTHER_VEHICLE);
    }

    /**
     * @param $motTestNumber
     *
     * @return \Zend\Http\Response
     */
    private function createDraft($motTestNumber)
    {
        $draftId = $this->getRestClient()->post(
            UrlBuilder::replacementCertificateDraft(),
            ['motTestNumber' => $motTestNumber]
        )['data']['id'];

        return $this->redirectToDraft($draftId);
    }

    /**
     * @param $id
     *
     * @return null|\Zend\Http\Response|ViewModel
     */
    private function updateDraft($id)
    {
        $action = $this->params()->fromPost('action');
        $result = $this->getUpdateData($action);

        // if input is not empty, update the draft
        if (!empty($result)) {
            if ($action == 'updateMake') {
                $make = $result['make'];
                if ($make == self::MAKE_MODEL_OTHER_VALUE) {
                    return $this->redirect()->toRoute('replacement-certificate/other-vehicle', ['id' => $id]);
                } else {
                    return $this->redirect()->toRoute(
                        'replacement-certificate/select-model',
                        ['id' => $id, 'makeCode' => $result['make']]
                    );
                }
            }

            if ($action == 'updateModel') {
                $model = $result['model'];
                $make = $result['make'];

                if (empty($make)) {
                    $make = self::MAKE_MODEL_OTHER_VALUE;
                } else {
                    $make = $result['make'];
                }

                if ($model == self::MAKE_MODEL_OTHER_VALUE) {
                    return $this->redirect()->toRoute(
                        'replacement-certificate/other-vehicle',
                        ['id' => $id, 'makeCode' => $make]
                    );
                }
            }

            try {

                if (self::ACTION_UPDATE_ODOMETER == $action) {

                    $odometerForm = $this->getForm(new OdometerUpdate());
                    $odometerForm->setData($this->mapOdometerReadingForValidator($result));

                    if (!$odometerForm->isValid()) {
                        $this->addErrorMessages(MotTestController::ODOMETER_FORM_ERROR_MESSAGE);
                        return $this->redirect()->toUrl(UrlBuilderWeb::replacementCertificate($id));
                    }
                }

                $this->getRestClient()->put(
                    UrlBuilder::replacementCertificateDraft($id),
                    $result
                );
            } catch (ValidationException $ve) {
                $this->addErrorMessages($ve->getDisplayMessages());

                return $this->redirect()->refresh();
            } catch (NotFoundException $e) {
                $this->addErrorMessages(self::SITE_NOT_FOUND);

                return $this->redirect()->refresh();
            }
        }

        $apiUrl = UrlBuilder::replacementCertificateDraftDiff($id);
        $diff = $this->getRestClient()->get($apiUrl)['data'];

        if (empty($diff)) {
            $this->addErrorMessages(self::INTACT_CERT_DETAIL);
        } elseif ($action === 'updateCertificate') {
            return $this->redirect()->toUrl(UrlBuilderWeb::replacementCertificateSummary($id));
        }

        return $this->redirectToDraft($id);
    }

    /**
     * @param string $updateAction
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getUpdateData($updateAction)
    {
        $post = $this->params()->fromPost();
        switch ($updateAction) {
            case 'updateCertificate':
                return $this->hasAdminRights() ? ['reasonForReplacement' => $post['reasonForReplacement']] : [];
            case 'updateVts':
                return ['vtsSiteNumber' => $post['vts']];
            case 'updateVin':
                return ['vin' => $post['vin']];
            case 'updateVrm':
                return ['vrm' => $post['vrm']];
            case 'updateColours':
                return [
                    'primaryColour' => $post['primaryColour'],
                    'secondaryColour' => $post['secondaryColour'] ?: null
                ];
            case 'updateMake':
                return [
                    'make' => $post['make']
                ];
            case 'updateModel':
                return [
                    'make' => $post['make'],
                    'model' => $post['model']
                ];
            case 'updateCustomMakeModel':
                return [
                    'customMake' => $post['make'],
                    'customModel' => $post['model']
                ];
            case 'updateMakeCustomModel':
                return [
                    'make' => $post['make'],
                    'customModel' => $post['model']
                ];
            case self::ACTION_UPDATE_ODOMETER:
                list($value, $unit, $resultType)
                    = [(int)$post['odometerValue'], $post['odometerUnit'], $post['odometerResultType']];

                if ($resultType !== OdometerReadingResultType::OK) {
                    $value = $unit = null;
                }

                return [
                    'odometerReading' => [
                        'value' => $value,
                        'unit' => $unit,
                        'resultType' => $resultType
                    ]
                ];
            case 'updateCor':
                return ['countryOfRegistration' => (int)$post['cor']];
            default:
                throw new \InvalidArgumentException("$updateAction action is invalid");
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    private function getDraftData($id)
    {
        $apiPath = UrlBuilder::replacementCertificateDraft($id);

        return ArrayUtils::tryGet($this->getRestClient()->get($apiPath), 'data');
    }

    /**
     * @param string $id
     * @param bool $makeCode
     * @return ViewModel
     */
    private function showDraft($id, $makeCode = false)
    {
        $draft = $this->getDraftData($id);
        $viewData = $this->buildViewData($draft, $makeCode);

        $viewData = array_merge(
            $viewData,
            [
                'isAdmin' => $this->hasAdminRights(),
                'isTester' => $this->hasTesterRights()
            ]
        );

        return (new ViewModel($viewData))->setTemplate(self::TEMPLATE_REPLACEMENT_CERTIFICATE);
    }

    /**
     * @param $model string
     * @return array
     */
    private function getStaticData($model)
    {
        /** @var CatalogService $catalogService */
        $catalogService = $this->getCatalogService();

        $colourMap = $catalogService->getColours();
        asort($colourMap);

        return [
            'colours' => new ColoursContainer($catalogService->getColours()),
            'countryOfRegistrationList' => $catalogService->getCountriesOfRegistration(),
            'makeList' => $this->vehicleCatalogService->findMake(),
            'modelList' => $this->vehicleCatalogService->findModel(false, $model)
        ];
    }

    /**
     * @param $id
     *
     * @return \Zend\Http\Response
     */
    private function redirectToDraft($id)
    {
        return $this->redirect()->toUrl(UrlBuilderWeb::replacementCertificate($id));
    }

    /**
     * @param $draft
     *
     * @return OdometerReadingViewObject
     */
    private function buildOdometerReadingViewObject($draft)
    {
        $odometerVO = new OdometerReadingViewObject();
        $odometerVO->setOdometerReadingValuesMap($draft['odometerReading']);

        $apiUrl = MotTestUrlBuilder::odometerReadingModifyCheck($draft['motTestNumber'])->toString();

        if (!$this->hasAdminRights()) {
            $apiResult = $this->getRestClient()->get($apiUrl);

            $odometerModifiable = $apiResult['data']['modifiable'];
            $odometerVO->setModifiable($odometerModifiable);
        }

        return $odometerVO;
    }

    /**
     * @param array $draftData
     * @param string $makeCode
     * @return array
     * @throws \Exception
     */
    private function buildViewData($draftData, $makeCode)
    {
        $readingVO = $this->buildOdometerReadingViewObject($draftData);

        $motTestNumber = ArrayUtils::tryGet($draftData, 'motTestNumber');

        $vehicleViewModel = new ReplacementVehicleViewModel($draftData);

        if ($makeCode) {
            $vehicleViewModel->getMake()->setCode($makeCode);
            $vehicleViewModel->setDisplayModelBody(true);
        }

        if ($makeCode == self::MAKE_MODEL_OTHER_VALUE) {
            $makeCode = null;
        } else {
            $makeCode = $vehicleViewModel->getMake()->getCode();
        }

        $staticData = $this->getStaticData($makeCode);

        $selectedMake = $this->getSelectedMakeOption($makeCode, $staticData['makeList']);

        if ($selectedMake) {
            $vehicleViewModel->setMake($selectedMake);
        }

        $viewData = array_merge(
            [
                'odometerReading' => $readingVO,
                'motTestNumber' => $motTestNumber,
                'motTest' => $this->tryGetMotTestOrAddErrorMessages($motTestNumber),
                'vts' => new ReplacementSiteViewModel($draftData),
                'vehicle' => $vehicleViewModel
            ],
            $staticData
        );

        return $viewData;
    }

    /**
     * @param string $makeCode
     * @param array $makeList
     * @return bool|ReplacementMakeViewModel
     * @throws \Exception
     */
    private function getSelectedMakeOption($makeCode, array $makeList)
    {
        if (!$makeCode) {
            return false;
        }

        foreach ($makeList as $make) {
            if ($makeCode == $make['code']) {
                $modelView = new ReplacementMakeViewModel($make);
                return $modelView;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function hasAdminRights()
    {
        return $this->getAuthorizationService()->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS);
    }

    /**
     * @return bool
     */
    private function hasTesterRights()
    {
        return $this->getAuthorizationService()->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);
    }

    /**
     * @return bool
     */
    private function canTestWithoutOtp()
    {
        return $this->getAuthorizationService()->isGranted(PermissionInSystem::MOT_TEST_WITHOUT_OTP);
    }

    private function mapOdometerReadingForValidator($result)
    {
        $data = $result['odometerReading'];
        $data['odometer'] = $data['value'];
        unset($data['value']);

        return $data;
    }

}
