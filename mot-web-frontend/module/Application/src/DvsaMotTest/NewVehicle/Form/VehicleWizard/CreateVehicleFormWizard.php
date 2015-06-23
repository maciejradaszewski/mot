<?php
namespace DvsaMotTest\NewVehicle\Form\VehicleWizard;

class CreateVehicleFormWizard
{
    /**
     * @var WizardStep[]
     */
    private $steps = [];

    /**
     * @param WizardStep $step
     */
    public function addStep(WizardStep $step)
    {
        $this->steps[$step::getName()] = $step;
    }

    /**
     * @param $name
     * @return WizardStep
     * @throws \Exception
     */
    public function getStep($name)
    {
        if (array_key_exists($name, $this->steps)) {
            return $this->steps[$name];
        }

        throw new \Exception("Step \"" . $name . "\" not found");
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->steps as $step) {
            if (!$step->isValid()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public function isStepValid($name)
    {
        $step = $this->getStep($name);
        return $step->isValid();
    }

    public function clear()
    {
        foreach ($this->steps as $step) {
            $step->clearData();
        }
    }
}
