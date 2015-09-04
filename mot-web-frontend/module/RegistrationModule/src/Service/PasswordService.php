<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Service;

use DvsaCommon\Validator\PasswordValidator;

/**
 * PasswordService validates passwords.
 */
class PasswordService
{
    /**
     * @var PasswordValidator
     */
    private $passwordValidator;

    /**
     * @param PasswordValidator $passwordValidator
     */
    public function __construct(PasswordValidator $passwordValidator)
    {
        $this->passwordValidator = $passwordValidator;
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return $this->passwordValidator->isValid($password);
    }
}
