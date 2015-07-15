<?php

namespace DvsaClient\ViewModel;

use DvsaCommon\Dto\Contact\EmailDto;
use Zend\Stdlib\Parameters;

/**
 * Model for changing email address in web forms
 */
class EmailFormModel extends AbstractFormModel
{
    const FIELD_EMAIL = 'email';
    const FIELD_EMAIL_CONFIRM = 'emailConfirmation';
    const FIELD_IS_NOT_SUPPLY = 'isEmailNotSupply';
    const FIELD_IS_PRIMARY = 'isEmailPrimary';

    const ERR_INVALID = 'The email you entered is not valid';
    const ERR_CONF_NOT_SAME = 'The confirmation email you entered is different';

    /**
     * @var  string
     */
    private $email;
    /**
     * @var  string
     */
    private $emailConfirm;
    /**
     * @var  boolean
     */
    private $isPrimary;
    /**
     * @var  boolean
     */
    private $isSupplied = true;


    public function fromPost(Parameters $postData)
    {
        $this
            ->setEmail($postData->get(self::FIELD_EMAIL))
            ->setEmailConfirm($postData->get(self::FIELD_EMAIL_CONFIRM))
            // there is negative question "I don't want to supply an email address", therefore
            // there need to set opposite value (if get true, set false, and in other way)
            ->setIsSupplied(
                (bool) $postData->get(self::FIELD_IS_NOT_SUPPLY) === false
            );

        return $this;
    }

    /**
     * @return EmailDto
     */
    public function toDto()
    {
        $dto = (new EmailDto())
            ->setIsPrimary($this->isPrimary());

        //  if user don't want provide email, but email was already exists in db,
        // there send EmailDto object without email, and it will delete this email in db
        if ($this->isSupplied() === true) {
            $dto->setEmail($this->getEmail());
        }

        return $dto;
    }

    public function fromDto($dto)
    {
        if ($dto instanceof EmailDto) {
            $this->setEmail($dto->getEmail())
                ->setEmailConfirm($dto->getEmail())
                ->setIsPrimary($dto->getIsPrimary());
        }

        return $this;
    }

    public function isValid()
    {
        if ($this->isSupplied()) {
            $email = $this->getEmail();

            $validator = new \Zend\Validator\EmailAddress();
            if ($validator->isValid($email) === false) {
                $this->addError(
                    EmailFormModel::FIELD_EMAIL,
                    self::ERR_INVALID
                );
            }

            if (strtolower($email) != strtolower($this->getEmailConfirm())) {
                $this->addError(
                    EmailFormModel::FIELD_EMAIL_CONFIRM,
                    self::ERR_CONF_NOT_SAME
                );
            }
        }

        return !$this->hasErrors();
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = trim($email);
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailConfirm()
    {
        return $this->emailConfirm;
    }

    /**
     * @param string $emailConfirm
     *
     * @return $this
     */
    public function setEmailConfirm($emailConfirm)
    {
        $this->emailConfirm = trim($emailConfirm);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSupplied()
    {
        return $this->isSupplied;
    }

    /**
     * @param boolean $isSupply
     *
     * @return $this
     */
    public function setIsSupplied($isSupply)
    {
        $this->isSupplied = ($isSupply === null ? true : (bool) $isSupply);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->isPrimary;
    }

    /**
     * @param boolean $isPrimary
     *
     * @return $this
     */
    public function setIsPrimary($isPrimary)
    {
        $this->isPrimary = (bool) $isPrimary;
        return $this;
    }
}
