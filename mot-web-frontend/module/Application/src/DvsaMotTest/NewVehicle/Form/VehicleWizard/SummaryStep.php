<?php
namespace DvsaMotTest\NewVehicle\Form\VehicleWizard;

use DvsaMotTest\NewVehicle\Fieldset\CreateVehicleStepOneFieldset;
use DvsaMotTest\NewVehicle\Fieldset\CreateVehicleStepTwoFieldset;
use DvsaMotTest\Model\Vehicle;
use DvsaMotTest\NewVehicle\Form\CreateVehicleStepThreeForm;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\DateSelect;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\UrlBuilder\VehicleUrlBuilder;
use DvsaMotTest\NewVehicle\Container\NewVehicleContainer;
use DvsaCommon\HttpRestJson\Client;
use Application\Service\CatalogService;
use Core\Service\MotFrontendIdentityProviderInterface;
use Core\Service\RemoteAddress;

class SummaryStep extends AbstractStep implements WizardStep
{
    /** @var MotFrontendIdentityProviderInterface */
    private $identityProvider;

    public function __construct(
        NewVehicleContainer $container,
        Client $client,
        CatalogService $catalogService,
        MotFrontendIdentityProviderInterface $identityProvider
    ) {
        parent::__construct($container, $client, $catalogService);

        $this->identityProvider = $identityProvider;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return "step_three";
    }

    /**
     * @return CreateVehicleStepThreeForm
     */
    public function createForm()
    {
        return new CreateVehicleStepThreeForm();
    }

    /**
     * @param Form $form
     * @return array
     */
    public function saveForm(Form $form)
    {
        $formData = $this->getFormData($form);
        $stepsData = $this->getDataFromAllSteps();

        $data = $stepsData + $formData ;
        $data['vtsId'] = $this->identityProvider->getIdentity()->getCurrentVts()->getVtsId();
        $data['clientIp'] = RemoteAddress::getIp();
        $apiUrl = VehicleUrlBuilder::vehicle();

        return $this->client->postJson($apiUrl, $data);
    }

    public function clearData()
    {
        $this->container->clear(AbstractStep::API_DATA);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $staticData = $this->getStaticData();

        return [
            'sectionOneData' => $this->prepareStepOneConfirmViewData($staticData),
            'sectionTwoData' => $this->prepareStepTwoConfirmViewData($staticData)
        ];
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return true;
    }

    /**
     * @param array $vehicleData
     * @return array
     */
    private function prepareStepOneConfirmViewData(array &$vehicleData)
    {
        $otherKey = CreateVehicleStepOneFieldset::LABEL_OTHER_KEY;
        $stepOneData = $this->getVehicleIdentificationStepData();
        if ($stepOneData['vehicleForm']['make'] == $otherKey) {
            unset ($vehicleData['make']);
        }

        $form = $this->getVehicleIdentificationStep()->createForm();
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

    /**
     * @param array $vehicleData
     * @return array
     */
    private function prepareStepTwoConfirmViewData(array &$vehicleData)
    {
        $otherKey = CreateVehicleStepOneFieldset::LABEL_OTHER_KEY;

        $stepTwoData = $this->getVehicleSpecificationStepData();
        if ($stepTwoData['vehicleForm']['model'] == CreateVehicleStepTwoFieldset::LABEL_OTHER_KEY) {
            unset ($vehicleData['model']);
        }

        $form = $this->getVehicleSpecificationStep()->createForm();
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

    /**
     * @return callable
     */
    private static function confirmationItemBuilder()
    {
        return function ($id, $label, $value) {
            return ['id' => $id, 'label' => $label, 'value' => $value];
        };
    }

    /**
     * @param DateSelect $f
     * @return array
     */
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

    /**
     * @return array
     */
    private function getDataFromAllSteps()
    {
        $stepOneData = $this->getVehicleIdentificationStepData();
        $stepTwoData = $this->getVehicleSpecificationStepData();
        $vehicle = new Vehicle();

        //Amending variable keys to pass through the Vehicle Model
        $stepTwoData['vehicleForm']['testClass'] = $stepTwoData['vehicleForm']['vehicleClass'];

        foreach ($stepOneData['vehicleForm'] as $k => $v) {
            if (empty($v)) {
                $v = null;
            }
            $stepOneData['vehicleForm'][$k] = $v;
        }

        //Merging all 3 forms together and populating the form as a whole
        $vehicle->populate(
            array_merge(
                $stepOneData['vehicleForm'],
                $stepTwoData['vehicleForm']
            )
        );

        return $vehicle->toArray();
    }

    /**
     * @return array
     */
    private function getVehicleIdentificationStepData()
    {
        return $this->getVehicleIdentificationStep()->getData();
    }

    /**
     * @return array
     */
    private function getVehicleSpecificationStepData()
    {
        return $this->getVehicleSpecificationStep()->getData();
    }

    /**
     * @return WizardStep
     */
    private function getVehicleIdentificationStep()
    {
        return $this->getVehicleSpecificationStep()->getPrevStep();
    }

    /**
     * @return WizardStep
     */
    private function getVehicleSpecificationStep()
    {
        return $this->getPrevStep();
    }
}
