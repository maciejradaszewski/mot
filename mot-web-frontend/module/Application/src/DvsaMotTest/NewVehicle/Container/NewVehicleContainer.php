<?php

namespace DvsaMotTest\NewVehicle\Container;

use DvsaCommon\Enum\ColourCode;
use Zend\Form\Form;
use Zend\Session\Container;
use Zend\Stdlib\ArrayObject;

class NewVehicleContainer
{
    const STEP_ONE = "step_one";
    const STEP_TWO = "step_two";
    const API_DATA = "api_data";

    private $cont;

    public function __construct(ArrayObject $containerImpl)
    {
        $this->cont = $containerImpl;
    }

    /**
     * @return array
     */
    public function getStepOneFormData()
    {
        if ($this->cont->offsetExists(self::STEP_ONE)) {
            return $this->cont->offsetGet(self::STEP_ONE);
        }

        return [];
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setStepOneFormData(Form $form)
    {
        $this->cont->offsetSet(self::STEP_ONE, $this->getFormData($form));

        return $this;
    }

    /**
     * @return array
     */
    public function getStepTwoFormData()
    {
        if ($this->cont->offsetExists(self::STEP_TWO)) {
            return $this->cont->offsetGet(self::STEP_TWO);
        }

        return ['vehicleForm' => ['secondaryColour' => ColourCode::NOT_STATED]];
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setStepTwoFormData(Form $form)
    {
        $data = $this->getFormData($form);

        unset($data['submit']);
        unset($data['back']);

        $this->cont->offsetSet(self::STEP_TWO, $data);

        return $this;
    }

    /**
     * @return array
     */
    public function getApiData()
    {
        if ($this->cont->offsetExists(self::API_DATA)) {
            return $this->cont->offsetGet(self::API_DATA);
        }

        return [];
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setApiData(array $data)
    {
        $this->cont->offsetSet(self::API_DATA, $data);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearAllData()
    {
        $this->clearOffset(self::STEP_ONE);
        $this->clearOffset(self::STEP_TWO);
        $this->clearOffset(self::API_DATA);

        return $this;
    }

    private function clearOffset($offset)
    {
        if ($this->cont->offsetExists($offset)) {
            $this->cont->offsetUnset($offset);
        }
    }

    /**
     * @param Form $form
     *
     * @return array|object
     */
    private function getFormData(Form $form)
    {
        if (!$form->hasValidated()) {
            //form cannot return data as validation has not yet occurred
            $form->isValid();
        }

        return $form->getData();
    }
}
