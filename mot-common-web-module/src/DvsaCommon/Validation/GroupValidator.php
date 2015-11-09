<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\Validation;

/**
 * Data validator.
 */
interface GroupValidator
{
    /**
     * @param array $data
     *
     * @return ValidationResult
     */
    public function validate(array $data);
}
