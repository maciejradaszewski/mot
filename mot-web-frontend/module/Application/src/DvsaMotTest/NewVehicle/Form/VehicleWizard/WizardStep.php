<?php
namespace DvsaMotTest\NewVehicle\Form\VehicleWizard;

use Zend\Form\Form;

interface WizardStep
{
    /**
     * @return Form
     */
    public function createForm();

    /**
     * @param Form $form
     * @return mix|void
     */
    public function saveForm(Form $form);

    /**
     * @return array
     */
    public function getData();

    /**
     * @return void
     */
    public function clearData();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return string
     */
    public static function getName();

    /**
     * @param WizardStep $step
     * @return void
     */
    public function setPrevStep(WizardStep $step);

    /**
     * @return WizardStep
     */
    public function getPrevStep();
}
