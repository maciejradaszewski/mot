<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\Validation;

use DvsaCommon\Validation\ValidationResult;
use PHPUnit_Framework_TestCase;

/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

class ValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testIsValid()
    {
        foreach ([true, false] as $isValid) {
            $validationResult = new ValidationResult($isValid);
            $this->assertEquals($isValid, $validationResult->isValid());
        }
    }
}
