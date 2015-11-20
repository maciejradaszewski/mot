<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\HttpRestJson\Exception;

use DvsaCommon\HttpRestJson\Exception\ValidationException;
use Exception;

class ValidationExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetValidationMessagesWithEmptyErrorMessages()
    {
        $exception = new ValidationException('', '', new Exception(), 0, []);
        $this->assertEquals([], $exception->getValidationMessages());
    }

    public function testGetValidationMessagesWithErrorMessages()
    {
        $messages = ['key' => 'validation message'];
        $errors = ['problem' => ['validation_messages' => $messages]];

        $exception = new ValidationException('', '', new Exception(), 0, $errors);
        $this->assertEquals($messages, $exception->getValidationMessages());
    }
}
