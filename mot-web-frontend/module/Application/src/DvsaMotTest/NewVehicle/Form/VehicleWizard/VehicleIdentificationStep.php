<?php
namespace DvsaMotTest\NewVehicle\Form\VehicleWizard;

use DvsaMotTest\NewVehicle\Form\CreateVehicleStepOneForm;
use Zend\Form\Form;

class VehicleIdentificationStep extends AbstractStep implements WizardStep
{
    /**
     * @return string
     */
    public static function getName()
    {
        return "step_one";
    }

    /**
     * @return CreateVehicleStepOneForm|Form
     */
    public function createForm()
    {
        $form = new CreateVehicleStepOneForm(
            [
                'vehicleData' => $this->getStaticData(),
            ]
        );

        return $form;
    }

    /**
     * @param Form $form
     */
    public function saveForm(Form $form)
    {
        $data = $this->getFormData($form);
        $this->container->set(self::getName(), $data);
    }

    public function clearData()
    {
        $this->container->clear(self::getName());
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->container->get(self::getName());
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $data = $this->getData();
        $form = $this->createForm();
        $form->setData($data);

        return $form->isValid();
    }
}
