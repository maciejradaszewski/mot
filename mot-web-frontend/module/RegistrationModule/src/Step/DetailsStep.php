<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Step;

use DvsaCommon\InputFilter\Registration\DetailsInputFilter;

class DetailsStep extends AbstractRegistrationStep
{
    /**
     * const used for the session key, available via getId().
     */
    const STEP_ID = "DETAILS";

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $confirmEmailAddress;

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
     * @return array
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
        if (is_array($values) && count($values)) {
            $this->setFirstName($values[DetailsInputFilter::FIELD_FIRST_NAME]);
            $this->setMiddleName($values[DetailsInputFilter::FIELD_MIDDLE_NAME]);
            $this->setLastName($values[DetailsInputFilter::FIELD_LAST_NAME]);
            $this->setEmailAddress($values[DetailsInputFilter::FIELD_EMAIL]);
            $this->setConfirmEmailAddress($values[DetailsInputFilter::FIELD_EMAIL_CONFIRM]);
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
            DetailsInputFilter::FIELD_FIRST_NAME     => $this->getFirstName(),
            DetailsInputFilter::FIELD_MIDDLE_NAME    => $this->getMiddleName(),
            DetailsInputFilter::FIELD_LAST_NAME      => $this->getLastName(),
            DetailsInputFilter::FIELD_EMAIL          => $this->getEmailAddress(),
            DetailsInputFilter::FIELD_EMAIL_CONFIRM  => $this->getConfirmEmailAddress(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCleanFilterWhiteList()
    {
        return [
            DetailsInputFilter::FIELD_FIRST_NAME,
            DetailsInputFilter::FIELD_MIDDLE_NAME,
            DetailsInputFilter::FIELD_LAST_NAME,
            DetailsInputFilter::FIELD_EMAIL,
            DetailsInputFilter::FIELD_EMAIL_CONFIRM,
        ];
    }

    /**
     * The route for this step.
     *
     * @return mixed
     */
    public function route()
    {
        return 'account-register/details';
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
        return "Step 1 of 6";
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getConfirmEmailAddress()
    {
        return $this->confirmEmailAddress;
    }

    /**
     * @param string $confirmEmailAddress
     */
    public function setConfirmEmailAddress($confirmEmailAddress)
    {
        $this->confirmEmailAddress = $confirmEmailAddress;
    }
}
