<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;

class Vm988BrakeTestResultsGradientClass1And2 extends BrakeTestClass1And2Base
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_1;

    /**
     * @param mixed $pass1
     */
    public function setControl1Above30($pass1)
    {
        $this->brakeTestForm['gradientControl1AboveUpperMinimum'] = $this->convertFromYesOrNo($pass1);
    }

    /**
     * @param mixed $pass2
     */
    public function setControl2Above30($pass2)
    {
        $this->brakeTestForm['gradientControl2AboveUpperMinimum'] = $this->convertFromYesOrNo($pass2);
    }

    /**
     * @param mixed $pass1
     */
    public function setControl1Below25($pass1)
    {
        $this->brakeTestForm['gradientControl1BelowMinimum'] = $this->convertFromYesOrNo($pass1);
    }

    /**
     * @param mixed $pass2
     */
    public function setControl2Below25($pass2)
    {
        $this->brakeTestForm['gradientControl2BelowMinimum'] = $this->convertFromYesOrNo($pass2);
    }

    public function beforeExecute()
    {
        $this->brakeTestForm['brakeTestType'] = BrakeTestTypeCode::GRADIENT;
    }

    protected function convertFromYesOrNo($value)
    {
        if ($value === 'YES') {
            return true;
        }
        if ($value === 'NO') {
            return false;
        }
        return null;
    }

    public function checkSavedCorrectly($response)
    {
        $expected = $this->brakeTestForm;
        /*
         * Following discussion with Radek, we don't test these fields as they are not stored.
         */
        unset($expected['gradientControl1AboveUpperMinimum']);
        unset($expected['gradientControl2AboveUpperMinimum']);
        return (new \MotFitnesse\Testing\MotTest\MotTestRetrieveCheckingHelper($this->motTestNumber))->savedCorrectly(
            $expected,
            $response,
            'brakeTestResult',
            ['gradientControl1BelowMinimum', 'gradientControl2BelowMinimum', 'brakeTestType']
        );
    }
}
