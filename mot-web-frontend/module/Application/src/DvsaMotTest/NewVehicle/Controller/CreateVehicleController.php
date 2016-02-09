<?php

namespace DvsaMotTest\NewVehicle\Controller;

use Application\Service\ContingencySessionManager;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\HttpRestJson\Exception\OtpApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\Model\FuelTypeAndCylinderCapacity;
use DvsaCommon\UrlBuilder\MotTestUrlBuilderWeb;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\CreateVehicleFormWizard;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\SummaryStep;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\VehicleIdentificationStep;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\VehicleSpecificationStep;
use Zend\Form\Element;
use Zend\Form\Element\DateSelect;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * @description Vehicle Generation as part of the MOT TEST process
 */
class CreateVehicleController extends AbstractDvsaMotTestController
{
    const ROUTE = 'vehicle-step';

    /** @var MotAuthorisationServiceInterface */
    private $authorisationService;

    /** @var  CreateVehicleFormWizard */
    private $wizard;
    /**
     * @var ContingencySessionManager
     */
    private $contingencySessionManager;

    public function __construct(
        CreateVehicleFormWizard $wizard,
        MotAuthorisationServiceInterface $authorisationService,
        Request $request,
        ContingencySessionManager $contingencySessionManager
    ) {
        $this->authorisationService = $authorisationService;
        $this->request = $request;
        $this->wizard = $wizard;
        $this->contingencySessionManager = $contingencySessionManager;
    }

    public function onDispatch(MvcEvent $e)
    {
        $this->assertGranted(PermissionInSystem::VEHICLE_CREATE);
        parent::onDispatch($e);
    }

    public function indexAction()
    {
        $this->wizard->clear();

        return $this->redirect()->toRoute(
            self::ROUTE,
            [
                'action' => 'add-step-one'
            ],
            [
                'query' => [
                    'reg' => $this->request->getQuery('reg')
                ]
            ]
        );
    }

    public function cancelAction()
    {
        $this->wizard->clear();
        return $this->redirect()->toRoute('vehicle-search');
    }

    public function addStepOneAction()
    {
        $step = $this->wizard->getStep(VehicleIdentificationStep::getName());
        $form = $step->createForm();

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                $step->saveForm($form);

                return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-two']);
            }

        } else {
            if ($this->params()->fromQuery('reg')) {
                $form->populateValues($form->setData([
                    'vehicleForm' =>
                        ['registrationNumber' => $this->params()->fromQuery('reg')]
                ]));
            }
            $form->populateValues($step->getData());
        }

        return (new ViewModel(['form' => $form]))->setTemplate('dvsa-mot-test/create-vehicle/add-step-one');
    }


    public function addStepTwoAction()
    {
        if (!$this->wizard->isStepValid(VehicleIdentificationStep::getName())) {
            return $this->redirect()->toRoute(self::ROUTE);
        }

        $step = $this->wizard->getStep(VehicleSpecificationStep::getName());
        $form = $step->createForm();

        if ($this->request->isPost() && $this->request->getPost('back')) {
            $form->setData($this->request->getPost());
            $step->saveForm($form);

            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-one']);
        } elseif ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                $step->saveForm($form);

                return $this->redirect()->toRoute(self::ROUTE, ['action' => 'confirm']);
            }
        } else {
            $form->populateValues($step->getData());
        }

        $cylinderCapacityRequired = FuelTypeAndCylinderCapacity::getAllFuelTypesWithCompulsoryCylinderCapacity(true);

        return (new ViewModel(
            [
                'form' => $form,
                'ccRequiredFuelType' => $cylinderCapacityRequired,
            ]
        ))->setTemplate('dvsa-mot-test/create-vehicle/add-step-two');
    }

    public function confirmAction()
    {
        if (!$this->wizard->isStepValid(VehicleIdentificationStep::getName())) {
            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-one']);
        } elseif (!$this->wizard->isStepValid(VehicleSpecificationStep::getName())) {
            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-two']);
        }

        $otpErrorData = [];
        $otpErrorMessage = null;
        $otpShortMessage = null;
        $step = $this->wizard->getStep(SummaryStep::getName());

        if ($this->request->isPost() && $this->request->getPost('back')) {
            return $this->redirect()->toRoute(self::ROUTE, ['action' => 'add-step-two']);
        } elseif ($this->request->isPost()) {
            try {
                $form = $step->createForm();
                $form->setData($this->request->getPost());
                $result = $step->saveForm($form);
                $motTestNumber = $result['data']['startedMotTestNumber'];

                $this->wizard->clear();

                if($this->contingencySessionManager->isMotContingency()) {
                    return $this->redirect()->toRoute("mot-test", ["motTestNumber" => $motTestNumber]);
                } else {
                    return $this->redirect()->toRoute("mot-test/options", ["motTestNumber" => $motTestNumber]);
                }

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

        $stepData = $step->getData();
        $canCreateVehicleWithoutOtp = $this->authorisationService->isGranted(PermissionInSystem::MOT_TEST_WITHOUT_OTP);

        return (new ViewModel(
            [
                'sectionOneData' => $stepData['sectionOneData'],
                'sectionTwoData' => $stepData['sectionTwoData'],
                'otpErrorData' => $otpErrorData,
                'canTestWithoutOtp' => $canCreateVehicleWithoutOtp,
                'otpErrorMessage' => $otpErrorMessage,
                'otpErrorShortMessage' => $otpShortMessage,
            ]
        ))->setTemplate('dvsa-mot-test/create-vehicle/confirm');
    }

    public function completeAction()
    {
        return (new ViewModel([]))->setTemplate('dvsa-mot-test/create-vehicle/complete');
    }
}
