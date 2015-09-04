<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Validation;

/**
 * Class ValidationResultTest.
 *
 * @group validation
 */
class ValidationResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValidationResult
     */
    private $validationResult;

    /**
     * Create new object to test.
     */
    public function setUp()
    {
        $this->validationResult = new ValidationResult();
    }

    /**
     * Ensure that errors is an empty array when the object is created.
     */
    public function testObjectCreationDefaults()
    {
        $this->assertInternalType('array', $this->validationResult->errors());
    }

    /**
     * test that we get the error back when we add an error.
     */
    public function testAddAnError()
    {
        $this->validationResult->addError(__METHOD__);
        $this->assertCount(1, $this->validationResult->errors());
        $this->assertSame(__METHOD__, $this->validationResult->errors()[0]);
    }

    /**
     * test that isValid returns true when there are no errors.
     */
    public function testValidationPassed()
    {
        $this->assertTrue($this->validationResult->isValid());
    }

    /**
     * test that isValid returns false when there are errors.
     */
    public function testValidationFailed()
    {
        $this->validationResult->addError(__METHOD__);
        $this->assertFalse($this->validationResult->isValid());
    }
}
