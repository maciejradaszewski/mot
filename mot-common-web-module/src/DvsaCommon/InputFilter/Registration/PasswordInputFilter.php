<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\InputFilter\Registration;

use DvsaCommon\Validator\PasswordValidator;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;

/**
 * (Account registration) Create a password's step input filter.
 *
 * Class PasswordInputFilter
 */
class PasswordInputFilter extends InputFilter
{
    /** Create a password */
    const FIELD_PASSWORD = 'password';
    const MSG_PASSWORD_EMPTY = 'you must enter a password';

    /** Re-type your password */
    const FIELD_PASSWORD_CONFIRM = 'passwordConfirm';
    const MSG_PASSWORD_CONFIRM_EMPTY = 'you must re-type your password';
    const MSG_PASSWORD_CONFIRM_DIFFER = 'the passwords you have entered don\'t match';

    public function init()
    {
        $this->initValidatorsForPassword();
        $this->initValidatorsForPasswordConfirmation();
    }

    /**
     * Adding validators for the password field/input.
     */
    private function initValidatorsForPassword()
    {
        $this->add(
            [
                'name'       => self::FIELD_PASSWORD,
                'required'   => true,
                'validators' => [
                    [
                        'name'    => NotEmpty::class,
                        'options' => [
                            'message' => self::MSG_PASSWORD_EMPTY,
                        ],
                    ],
                    [
                        'name' => PasswordValidator::class,
                    ],
                ],
            ]
        );
    }

    /**
     * Adding validators for the password confirmation field/input.
     */
    private function initValidatorsForPasswordConfirmation()
    {
        $this->add(
            [
                'name'       => self::FIELD_PASSWORD_CONFIRM,
                'required'   => true,
                'validators' => [
                    [
                        'name'    => NotEmpty::class,
                        'options' => [
                            'message' => self::MSG_PASSWORD_CONFIRM_EMPTY,
                        ],
                    ],
                    [
                        'name'    => Identical::class,
                        'options' => [
                            'token'   => self::FIELD_PASSWORD,
                            'message' => self::MSG_PASSWORD_CONFIRM_DIFFER,
                        ],
                    ],
                ],
            ]
        );
    }
}
