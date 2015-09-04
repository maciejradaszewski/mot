<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Step;

use DvsaCommon\InputFilter\Registration\PasswordInputFilter;

class PasswordStep extends AbstractRegistrationStep
{
    /**
     * const used for the session key, available via getId().
     */
    const STEP_ID = "PASSWORD";

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $passwordConfirm;

    /**
     * @return string
     */
    public function getId()
    {
        return self::STEP_ID;
    }

    /**
     * Load the steps data from the session storage.
     *
     * @return $this
     */
    public function load()
    {
        $values = $this->sessionService->load(self::STEP_ID);
        $this->readFromArray($values);

        return $this;
    }

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function readFromArray(array $values)
    {
        if (count($values)) {
            // Password steps must ignore any values posted that contain obfuscated data
//            if (strpos($values[PasswordInputFilter::FIELD_PASSWORD], '&bull;') !== false) {
//                return;
//            }

            $this->setPassword($values[PasswordInputFilter::FIELD_PASSWORD]);
            $this->setPasswordConfirm($values[PasswordInputFilter::FIELD_PASSWORD_CONFIRM]);
        }
    }

    /**
     * Export the step values as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            PasswordInputFilter::FIELD_PASSWORD         => $this->getPassword(),
            PasswordInputFilter::FIELD_PASSWORD_CONFIRM => $this->getPasswordConfirm(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCleanFilterWhiteList()
    {
        return [];
    }

    /**
     * The route for this step.
     *
     * @return mixed
     */
    public function route()
    {
        return 'account-register/password';
    }

    /**
     * describes the steps progress in the registration process.
     *
     * Step 1 of 6
     * Step 2 of 6
     * etc
     *
     * @return string|null
     */
    public function getProgress()
    {
        return "Step 5 of 6";
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * @param string $passwordConfirm
     */
    public function setPasswordConfirm($passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;
    }
}
