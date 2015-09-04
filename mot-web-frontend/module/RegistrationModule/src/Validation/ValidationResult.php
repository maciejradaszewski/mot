<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Validation;

/**
 * THis is moving to Validation namespace.
 */
class ValidationResult
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * Get the errors array.
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get the errors array.
     *
     * @return array
     */
    public function errorsSummary()
    {
        return $this->errors;
    }

    /**
     * Add a validation error.
     *
     * @param $error
     */
    public function addError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Check that there are no errors and the validation has passed OK.
     *
     * @return bool
     */
    public function isValid()
    {
        return count($this->errors) === 0;
    }
}
