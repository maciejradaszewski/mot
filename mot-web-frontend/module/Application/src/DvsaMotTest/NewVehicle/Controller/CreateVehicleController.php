<?php

namespace DvsaMotTest\NewVehicle\Controller;

use Application\Service\CatalogService;

use Application\Service\ContingencySessionManager;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\HttpRestJson\Exception\OtpApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\Model\FuelTypeAndCylinderCapacity;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\UrlBuilder\VehicleUrlBuilder;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use DvsaMotTest\NewVehicle\Fieldset\CreateVehicleStepOneFieldset;
use DvsaMotTest\NewVehicle\Fieldset\CreateVehicleStepTwoFieldset;
use DvsaMotTest\NewVehicle\Form\CreateVehicleStepOneForm;
use DvsaMotTest\NewVehicle\Form\CreateVehicleStepTwoForm;
use DvsaMotTest\Model\Vehicle;
use DvsaMotTest\Service\AuthorisedClassesService;
use DvsaMotTest\NewVehicle\Container\NewVehicleContainer;
use Vehicle\Helper\ColoursContainer;
use Zend\Form\Element;
use Zend\Form\Element\DateSelect;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * @description Vehicle Generation as part of the MOT TEST process
 */
class CreateVehicleController extends AbstractDvsaMotTestController
{
    const ROUTE = 'vehicle-step';

    /**  @var NewVehicleContainer */
    private $container;

    /** @var AuthorisedClassesService */
    private $authorisedClassesService;

    /** @var MotAuthorisationServiceInterface */
    private $authorisationService;

    /** @var  Client */
    private $client;

    /** @var  CatalogService */
    private $catalogService;

    /** @var  MotIdentityProviderInterface */
    private $identityProvider;

    public function __construct(
        AuthorisedClassesService $authorisedClassesService,
        CatalogService $catalogService,
        MotIdentityProviderInterface $identityProvider,
        MotAuthorisationServiceInterface $authorisationService,
        NewVehicleContainer $container,
        Request $request,
        Client $client
    ) {
        $this->authorisedClassesService = $authorisedClassesService;
        $this->authorisationService = $authorisationService;
        $this->identityProvider = $identityProvider;
        $this->catalogService = $catalogService;
        $this->container = $container;
        $this->request = $request;
        $this->client = $client;
    }

    public function onDispatch(MvcEvent $e)
    {
        $this->assertGranted(PermissionInSystem::VEHICLE_CREATE);
        parent::onDispatch($e);
    }

    public function indexAction()
    {
        $this->container->clearAllData();

        return $this->redirect()->toRoute(
            self::ROUTE,
            [
                'action' => 'add-step-one'
            ],
            [
                'query' => [
                    'vin' => $this->request->getQuery('vin'),
                    'reg' => $this->request->getQuery('reg')
                ]
            ]
        );
    }

    public function cancelAction()
    {
        $this->container->clearAllData();

        return $this->redirect()->toRoute('vehicle-search');
    }

    public function addStepOneAction()
    {
        $form = $this->createStepOneForm();

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                $this->container->setStepOneFormData($form);

                return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-two']);
            }

        } else {
            $this->populateStepOneFormData($form);
        }

        return (new ViewModel(['form' => $form]))->setTemplate('dvsa-mot-test/create-vehicle/add-step-one');
    }


    public function addStepTwoAction()
    {
        if (!$this->isStepOneFormValid()) {
            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-one']);
        }

        //Pre-populated data for the form
        $vehicleData = $this->getVehicleStaticData();
        $stepOneData = $this->container->getStepOneFormData();
        $makeCode = $stepOneData['vehicleForm']['make'];
        $isMakeOther = $makeCode == CreateVehicleStepOneFieldset::LABEL_OTHER_KEY;

        $models = $isMakeOther ? [[]] :
            $this->client->get(UrlBuilder::vehicleDictionary()->make($makeCode)->model()->toString());

        $vehicleData['model'] = end($models);
        $this->container->setApiData($vehicleData);

        $form = $this->createStepTwoForm($vehicleData['model']);

        if ($this->request->isPost() && $this->request->getPost('back')) {
            $form->setData($this->request->getPost());
            $this->container->setStepTwoFormData($form);

            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-one']);
        } elseif ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                $this->container->setStepTwoFormData($form);

                return $this->redirect()->toRoute(self::ROUTE, ['action' => 'confirm']);
            }
        } else {
            $form->populateValues($this->container->getStepTwoFormData());
        }

        $cylinderCapacityRequired = FuelTypeAndCylinderCapacity::getAllFuelTypesWithCompulsoryCylinderCapacity(true);

        return (new ViewModel(
            [
                'vehicleApiData' => $vehicleData,
                'form' => $form,
                'firstForm' => $stepOneData['vehicleForm'],
                'ccRequiredFuelType' => $cylinderCapacityRequired,
            ]
        ))->setTemplate('dvsa-mot-test/create-vehicle/add-step-two');
    }

    public function confirmAction()
    {
        if (!$this->isStepOneFormValid()) {
            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-one']);
        } elseif (!$this->isStepTwoFormValid()) {
            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-two']);
        }

        $vehicleData = $this->getVehicleStaticData();
        $otpErrorData = [];
        $otpErrorMessage = null;
        $otpShortMessage = null;
        $stepOneData = $this->container->getStepOneFormData();
        $stepTwoData = $this->container->getStepTwoFormData();

        if ($this->request->isPost() && $this->request->getPost('back')) {
            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-two']);
        } elseif ($this->request->isPost()) {
            //add the data here and add an exception
            $form = new Vehicle();

            //Amending variable keys to pass through the Vehicle Model
            $stepTwoData['vehicleForm']['testClass'] = $stepTwoData['vehicleForm']['vehicleClass'];

            foreach ($stepOneData['vehicleForm'] as $k => $v) {
                if (empty($v)) {
                    $v = null;
                }
                $stepOneData['vehicleForm'][$k] = $v;
            }

            //Merging all 3 forms together and populating the form as a whole
            $form->populate(
                array_merge(
                    $stepOneData['vehicleForm'],
                    $stepTwoData['vehicleForm']
                )
            );

            try {
                $data = $form->toArray() + $this->request->getPost()->toArray();
                $apiUrl = VehicleUrlBuilder::vehicle();

                $this->client->postJson($apiUrl, $data);

                $this->container->clearAllData();

                return $this->redirect()->toRoute(self::ROUTE, ['action' => 'complete']);
            } catch (OtpApplicationException $e) {
                $errorData = $e->getErrorData();

                if (isset($errorData['message'])) {
                    $message = $errorData['message'];
                    $this->addErrorMessages($message);
                    $otpErrorMessage = $message;
                }

                if (isset($errorData['shortMessage'])) {
                    $otpShortMessage = $errorData['shortMessage'];
                }
            } catch (ValidationException $ve) {
                $this->flashMessenger()->addMessage($ve->getDisplayMessages());
            }
        }


        $stepOneData = $this->prepareStepOneConfirmViewData($vehicleData);
        $stepTwoData = $this->prepareStepTwoConfirmViewData($vehicleData);

        $canCreateVehicleWithoutOtp = $this->authorisationService->isGranted(PermissionInSystem::MOT_TEST_WITHOUT_OTP);

        return (new ViewModel(
            [
                'sectionOneData' => $stepOneData,
                'sectionTwoData' => $stepTwoData,
                'otpErrorData' => $otpErrorData,
                'canTestWithoutOtp' => $canCreateVehicleWithoutOtp,
                'otpErrorMessage' => $otpErrorMessage,
                'otpErrorShortMessage' => $otpShortMessage
            ]
        ))->setTemplate('dvsa-mot-test/create-vehicle/confirm');
    }

    private function prepareStepOneConfirmViewData(&$vehicleData)
    {
        $otherKey = CreateVehicleStepOneFieldset::LABEL_OTHER_KEY;
        $stepOneData = $this->container->getStepOneFormData();
        if ($stepOneData['vehicleForm']['make'] == $otherKey) {
            unset ($vehicleData['make']);
        }

        $form = $this->createStepOneForm();
        $form->populateValues($stepOneData);

        $elems = $form->getFieldsets()['vehicleForm'];
        $fCor = $elems->get('countryOfRegistration');
        $fVrm = $elems->get('registrationNumber');
        $fEmptyVrmReason = $elems->get('emptyVrmReason');
        $fVin = $elems->get('VIN');
        $fEmptyVinReason = $elems->get('emptyVinReason');
        $fMake = $elems->get('make');
        $fMakeOther = $elems->get('makeOther');
        $fDateOfFirstUse = $elems->get('dateOfFirstUse');
        $fTransmissionType = $elems->get('transmissionType');

        $buildItem = self::confirmationItemBuilder();
        $data = [];
        $data[] = $this->getOptionLabelValuePair($fCor);
        $data[] = $buildItem(
            $fVrm->getValue() ? $fVrm->getName() : $fEmptyVrmReason->getName(),
            $fVrm->getLabel(),
            $fVrm->getValue() ?: $this->getSelectedOptionValue($fEmptyVrmReason)
        );
        $data[] = $buildItem(
            $fVin->getValue() ? $fVin->getName() : $fEmptyVinReason->getName(),
            $fVin->getLabel(),
            $fVin->getValue() ?: $this->getSelectedOptionValue($fEmptyVinReason)
        );
        $data[] = $buildItem(
            $fMake->getName(),
            $fMake->getLabel(),
            $fMake->getValue() === $otherKey ? $fMakeOther->getValue() : $this->getSelectedOptionValue($fMake)
        );
        $data[] = $this->getDatePair($fDateOfFirstUse);
        $data[] = $this->getOptionLabelValuePair($fTransmissionType);

        return $data;
    }


    private function prepareStepTwoConfirmViewData(&$vehicleData)
    {
        $otherKey = CreateVehicleStepOneFieldset::LABEL_OTHER_KEY;

        $stepTwoData = $this->container->getStepTwoFormData();
        if ($stepTwoData['vehicleForm']['model'] == CreateVehicleStepTwoFieldset::LABEL_OTHER_KEY) {
            unset ($vehicleData['model']);
        }

        $models = isset($vehicleData['model']) ? $vehicleData['model'] : [];
        $form = $this->createStepTwoForm($models);
        $form->populateValues($stepTwoData);

        $elems = $form->getFieldsets()['vehicleForm'];

        $data = [];
        $fModel = $elems->get('model');
        $fModelOther = $elems->get('modelOther');
        $fFuelType = $elems->get('fuelType');
        $fCylinderCapacity = $elems->get('cylinderCapacity');
        $fTestClass = $elems->get('vehicleClass');
        $fColour1 = $elems->get('colour');
        $fColour2 = $elems->get('secondaryColour');

        $buildItem = self::confirmationItemBuilder();

        $data[] = $buildItem(
            $fModel->getName(),
            $fModel->getLabel(),
            $fModel->getValue() === $otherKey ? $fModelOther->getValue() : $this->getSelectedOptionValue($fModel)
        );

        $data[] = $this->getOptionLabelValuePair($fFuelType);
        if (strlen($fCylinderCapacity->getValue()) > 0) {
            $data[] = $buildItem(
                $fCylinderCapacity->getName(),
                $fCylinderCapacity->getLabel(),
                $fCylinderCapacity->getValue()
            );
        }

        $data[] = $this->getOptionLabelValuePair($fTestClass);
        $data[] = $this->getOptionLabelValuePair($fColour1);
        $data[] = $this->getOptionLabelValuePair($fColour2);

        return $data;
    }

    private static function confirmationItemBuilder()
    {
        return function ($id, $label, $value) {
            return ['id' => $id, 'label' => $label, 'value' => $value];
        };
    }


    private function getDatePair(DateSelect $f)
    {
        $date = (new \DateTime())->setDate(
            $f->getYearElement()->getValue(),
            $f->getMonthElement()->getValue(),
            $f->getDayElement()->getValue()
        );

        $buildItem = self::confirmationItemBuilder();

        return $buildItem($f->getName(), $f->getLabel(), DateTimeDisplayFormat::date($date));
    }

    private function getOptionLabelValuePair(Element $f)
    {
        $buildItem = self::confirmationItemBuilder();

        return $buildItem($f->getName(), $f->getLabel(), $this->getSelectedOptionValue($f));
    }

    private function getSelectedOptionValue($field)
    {
        return $field->getValueOptions()[$field->getValue()];
    }


    public function completeAction()
    {
        /** @var ContingencySessionManager $contingencySession */
        $contingencySession = $this->getServiceLocator()->get(ContingencySessionManager::class);
        $isContingency = $contingencySession->isMotContingency();

        return (new ViewModel(compact('isContingency')))->setTemplate('dvsa-mot-test/create-vehicle/complete');
    }

    private function getVehicleStaticData()
    {
        $vehicleData = $this->container->getApiData();

        if (!empty($vehicleData)) {
            return $vehicleData;
        } else {
            $makes = $result = $this->client->get(UrlBuilder::vehicleDictionary()->make()->toString());

            $catalogData = $this->catalogService->getData();

            $colours = (new ColoursContainer($this->catalogService->getColours(), true));

            $fuelTypes = array_map(
                function ($fuelType) {
                    return [
                        'id' => $fuelType['code'],
                        'name' => $fuelType['name']
                    ];
                }, $catalogData['fuelTypes']
            );

            $vehicleData = [
                'make' => $makes['data'],
                'colour' => $colours->getPrimaryColours(),
                'secondaryColour' => $colours->getSecondaryColours(),
                'fuelType' => $fuelTypes,
                'countryOfRegistration' => $catalogData['countryOfRegistration'],
                'transmissionType' => array_reverse($catalogData['transmissionType'], true),
                'vehicleClass' => $catalogData['vehicleClass'],
                'model' => [],
                'emptyVrmReasons' => $catalogData['reasonsForEmptyVRM'],
                'emptyVinReasons' => $catalogData['reasonsForEmptyVIN'],
            ];

            $this->container->setApiData($vehicleData);

            return $vehicleData;
        }
    }

    private function createStepOneForm()
    {
        return new CreateVehicleStepOneForm(
            [
                'vehicleData' => $this->getVehicleStaticData(),
            ]
        );
    }

    private function createStepTwoForm(array $model)
    {
        $vehicleData = $this->getVehicleStaticData();

        if ($model) {
            $vehicleData = array_merge($vehicleData, ['model' => $model]);
        }

        $vehicleData = array_merge($vehicleData, ['authorisedClasses' => $this->getAuthorisedClassesForUserAndVTS()]);

        return new CreateVehicleStepTwoForm(
            [
                'vehicleData' => $vehicleData,
            ]
        );
    }

    private function getAuthorisedClassesForUserAndVTS()
    {
        $identity = $this->identityProvider->getIdentity();
        $userId = $identity->getUserId();

        $currentVts = $identity->getCurrentVts();
        if (!$currentVts) {
            throw new \Exception("VTS not found");
        }
        $siteId = $currentVts->getVtsId();

        $authorisedClassesCombined = $this->authorisedClassesService->getCombinedAuthorisedClassesForPersonAndVts(
            $userId,
            $siteId
        );

        return $authorisedClassesCombined;
    }

    /**
     * @return bool
     */
    private function isStepOneFormValid()
    {
        $form = $this->createStepOneForm();
        $form->setData($this->container->getStepOneFormData());

        return $form->isValid();
    }

    /**
     * @return bool
     */
    private function isStepTwoFormValid()
    {
        $model = $this->container->getApiData()['model'];
        $form = $this->createStepTwoForm($model);
        $form->setData($this->container->getStepTwoFormData());

        return $form->isValid();
    }

    private function populateStepOneFormData(&$form)
    {
        if ($this->params()->fromQuery('vin')) {
            $form->setData([
                'vehicleForm' =>
                    ['VIN' => $this->params()->fromQuery('vin')]
            ]);
        }

        if ($this->params()->fromQuery('reg')) {
            $form->setData([
                'vehicleForm' =>
                    ['registrationNumber' => $this->params()->fromQuery('reg')]
            ]);
        }

        $form->populateValues($this->container->getStepOneFormData());
    }
}
