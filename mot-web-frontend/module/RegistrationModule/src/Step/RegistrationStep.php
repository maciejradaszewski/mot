<?php

namespace Dvsa\Mot\Frontend\RegistrationModule\Step;

/**
 * Interface for a Registration step.
 */
interface RegistrationStep
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param array $values
     *
     * @return bool
     */
    public function validate(array $values = []);

    /**
     * @return RegistrationStep
     */
    public function load();

    /**
     * @return bool
     */
    public function save();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return array
     */
    public function toViewArray();

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function readFromArray(array $values);

    /**
     * The route for this step.
     *
     * @return mixed
     */
    public function route();

    /**
     * describes the steps progress in the registration process.
     *
     * Step 1 of 6
     * Step 2 of 6
     * etc
     *
     * @return string|null
     */
    public function getProgress();
}
