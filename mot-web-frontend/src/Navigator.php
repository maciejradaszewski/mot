<?php

namespace Dvsa\Mot\Frontend;

class Navigator
{
    const PARAM_NEXT_STEP = 'nextStep';
    private $steps;

    public function __construct($steps)
    {
        $this->steps = $steps;
    }

    public function getSteps()
    {
        return $this->steps;
    }

    public function getNavigationLinks($currentStep)
    {
        $navigator = array();

        $this->createNavigationLink($navigator, $this->getPreviousStep($currentStep), 'prev');
        $this->createNavigationLink($navigator, $this->getNextStep($currentStep), 'next');

        return $navigator;
    }

    private function getNextStep($currentStep)
    {
        list($stepKeys, $stepIndex) = $this->getStepKeysAndCurrentIndex($currentStep);

        if ($stepIndex < (count($stepKeys) - 1)) {
            return $stepKeys[$stepIndex + 1];
        }

        return '';
    }

    private function getPreviousStep($currentStep)
    {
        list($stepKeys, $stepIndex) = $this->getStepKeysAndCurrentIndex($currentStep);

        if ($stepIndex > 0) {
            return $stepKeys[$stepIndex - 1];
        }

        return '';
    }


    private function createNavigationLink(&$navigator, $step, $nextPrevString)
    {
        if (empty($step) === false) {
            $navigator[$nextPrevString] = array(
                'label'      => $this->steps[$step],
                'step'       => $step,
                'buttonName' => self::PARAM_NEXT_STEP,
            );
        }
    }

    private function getStepKeysAndCurrentIndex($currentStep)
    {
        $stepKeys = array_keys($this->steps);
        $stepIndex = array_search($currentStep, $stepKeys);

        return array($stepKeys, $stepIndex);
    }
}

?>