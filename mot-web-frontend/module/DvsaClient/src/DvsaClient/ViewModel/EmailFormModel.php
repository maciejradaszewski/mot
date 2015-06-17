<?php

namespace DvsaClient\ViewModel;

use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Utility\ArrayUtils;

/**
 * Model for changing email address in web forms
 */
class EmailFormModel
{
    public static $FIELD_EMAIL = 'email';
    public static $FIELD_EMAIL_CONFIRM = 'emailConfirmation';
    public static $FIELD_IS_SUPPLY = 'emailIsSupply';
    public static $FIELD_IS_PRIMARY = 'emailIsPrimary';

    /** @var  EmailDto */
    private $email;
    /** @var  string */
    private $emailConfirm;
    /** @var  boolean */
    private $isSupply = true;

    public function __construct($fieldPrefix = null)
    {
        $this->email = new EmailDto;

        $this->fieldPrefix = $fieldPrefix;
    }

    public function getFieldName($field)
    {
        if ($this->fieldPrefix !== null) {
            return $this->fieldPrefix . $field;
        }

        return $field;
    }

    /**
     * @return EmailDto
     */
    public function getDto()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email->getEmail();
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email->setEmail($email);
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
        $this->emailConfirm = $emailConfirm;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSupply()
    {
        return $this->isSupply;
    }

    /**
     * @param boolean $isSupply
     *
     * @return $this
     */
    public function setIsSupply($isSupply)
    {
        $this->isSupply = ($isSupply === null ? true : (bool)$isSupply);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->email->getIsPrimary();
    }

    /**
     * @param boolean $isPrimary
     *
     * @return $this
     */
    public function setIsPrimary($isPrimary)
    {
        $this->email->setIsPrimary((bool)$isPrimary);
        return $this;
    }


    public function fromDto($dto)
    {
        if ($dto instanceof EmailDto) {
            $this->email = $dto;
            $this->setEmailConfirm($dto->getEmail());
        }
    }

    public function fromPost(array $postData)
    {
        $dataSet = $postData;
        if ($this->fieldPrefix !== '') {
            //  --  looking for fields in format prefix . fieldName    --
            $dataSet = ArrayUtils::removePrefixFromKeys($postData, $this->fieldPrefix);
        }

        $this
            ->setEmail(ArrayUtils::tryGet($dataSet, self::$FIELD_EMAIL))
            ->setEmailConfirm(ArrayUtils::tryGet($dataSet, self::$FIELD_EMAIL_CONFIRM))
            ->setIsPrimary(ArrayUtils::tryGet($dataSet, self::$FIELD_IS_PRIMARY))
            ->setIsSupply(ArrayUtils::tryGet($dataSet, self::$FIELD_IS_SUPPLY));

        return $this;
    }

    public function toArray($withPrefix = true)
    {
        $data = [
            'email'             => $this->getEmail(),
            'emailConfirmation' => $this->getEmailConfirm(),
            'isPrimary'         => $this->isPrimary(),
            'isSupply'          => $this->isSupply(),
        ];

        if ($withPrefix === true) {
            $data = ArrayUtils::addPrefixToKeys($data, $this->fieldPrefix);
        }

        return $data;
    }
}
