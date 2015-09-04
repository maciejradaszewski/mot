<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Service;

use Dvsa\Mot\Frontend\RegistrationModule\Step\RegistrationStep;

/**
 * Service used to manage steps through the registration workflow.
 */
class RegistrationStepService implements \Countable, \IteratorAggregate
{
    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
    private $steps = [];

    /**
     * @var array
     */
    private $idLookup = [];

    /**
     * @param array $steps
     */
    public function __construct(array $steps = [])
    {
        $this->position = 0;

        foreach ($steps as $step) {
            $this->add($step);
        }
    }

    /**
     * Returns the next step.
     *
     * @return RegistrationStep|false
     */
    public function next()
    {
        if (!$this->isLast()) {
            ++$this->position;

            return $this->current();
        }

        return false;
    }

    /**
     * Returns the previous step.
     *
     * @return RegistrationStep|false
     */
    public function previous()
    {
        if ($this->position !== 0) {
            --$this->position;

            return $this->current();
        }

        return false;
    }

    /**
     * Returns the current step.
     *
     * @throws \Exception
     *
     * @return RegistrationStep
     */
    public function current()
    {
        if (!$this->exists()) {
            throw new \Exception("Current step not set");
        }

        return $this->steps[$this->position];
    }

    /**
     * Returns the position of the current pointer.
     *
     * @return int
     */
    private function position()
    {
        return $this->position;
    }

    /**
     * Sets the position back to the first element in the stack.
     *
     * @return $this
     */
    public function rewind()
    {
        $this->position = 0;

        return $this;
    }

    /**
     * Checks to see if the step is within bounds.
     *
     * @return bool
     */
    private function exists()
    {
        return isset($this->steps[$this->position]);
    }

    /**
     * @param string $id
     *
     * @throws \Exception if id does not exist
     *
     * @return RegistrationStep
     */
    public function getById($id)
    {
        $this->assertIdExists($id);
        $index = $this->idLookup[$id];

        return $this->steps[$index];
    }

    /**
     * @param RegistrationStep $step
     *
     * @throws \Exception if id already exists in the lookup
     *
     * @return int
     */
    public function add(RegistrationStep $step)
    {
        // Build the idLookup before adding to the steps;
        $index = count($this->steps);
        $id = $step->getId();
        if (isset($this->idLookup[$id])) {
            throw new \Exception("Step with id {$id} already exists");
        }
        $this->idLookup[$id] = $index;
        $this->steps[] = $step;

        return $index;
    }

    /**
     * Returns the RegistrationStep of the first step.
     *
     * @throws \Exception
     *
     * @return RegistrationStep
     */
    public function first()
    {
        $this->assertHasSteps();

        return $this->steps[0];
    }

    /**
     * Returns the RegistrationStep of the last step.
     *
     * @throws \Exception
     *
     * @return RegistrationStep
     */
    public function last()
    {
        $this->assertHasSteps();
        $index = count($this->steps) - 1;

        return $this->steps[$index];
    }

    /**
     * Checks to see if the current step is the last step.
     *
     * @return boolean
     */
    public function isLast()
    {
        $maxIndex = count($this->steps) - 1;

        return ($this->position === $maxIndex);
    }

    /**
     * Sets the current active step by Id.
     *
     * @param String $id
     *
     * @throws \Exception if the step does not exist
     *
     * @return RegistrationStepService
     */
    public function setActiveById($id)
    {
        $this->assertIdExists($id);
        $index = $this->idLookup[$id];
        $this->position = $index;

        return $this;
    }

    /**
     * Sets the active step by a RegistrationStep.
     *
     * @param RegistrationStep $registrationStep
     *
     * @throws \Exception if the step does not exist
     *
     * @return bool
     */
    public function setActiveByRegistrationStep(RegistrationStep $registrationStep)
    {
        $id = $registrationStep->getId();

        return $this->setActiveById($id);
    }

    /**
     * Gets the total number of steps that will be displayed on the frontend.
     *
     * @return int
     */
    public function count()
    {
        return count($this->steps);
    }

    /**
     * Gets the current step number - this will be displayed on the frontend.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position() + 1;
    }

    /**
     * Define how the steps can be iterated.
     *
     * @return array
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->steps);
    }

    /**
     * Checks to see if the steps array is empty.
     *
     * @throws \Exception
     */
    private function assertHasSteps()
    {
        if (empty($this->steps)) {
            throw new \Exception("Contains no steps");
        }
    }

    /**
     * @param String $id
     *
     * @throws \Exception
     */
    private function assertIdExists($id)
    {
        if (!isset($this->idLookup[$id])) {
            throw new \Exception("ID {$id} does not exist");
        }
    }
}
