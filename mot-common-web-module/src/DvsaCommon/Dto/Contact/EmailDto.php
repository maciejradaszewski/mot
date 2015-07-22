<?php

namespace DvsaCommon\Dto\Contact;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;

/**
 * Class EmailDto
 *
 * @package DvsaCommon\Dto\Contact
 */
class EmailDto extends AbstractDataTransferObject
{
    /** @var int */
    private $id;
    /** @var string */
    private $email;
    /** @var string */
    private $emailConfirm;
    /** @var  boolean */
    private $isPrimary;
    /** @var  boolean */
    private $isSupplied;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setIsPrimary($isPrimary)
    {
        $this->isPrimary = (bool) $isPrimary;
        return $this;
    }

    public function isPrimary()
    {
        return $this->isPrimary;
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
    public function isSupplied()
    {
        return $this->isSupplied;
    }

    /**
     * @param boolean $isSupplied
     * @return $this
     */
    public function setIsSupplied($isSupplied)
    {
        $this->isSupplied = $isSupplied;
        return $this;
    }

    /**
     * @param EmailDto $dtoA
     * @param EmailDto $dtoB
     *
     * @return boolean
     */
    public static function isEquals($dtoA, $dtoB)
    {
        return (
            ($dtoA === null && $dtoB === null)
            || (
                $dtoA instanceof EmailDto
                && $dtoB instanceof EmailDto
                && $dtoA->getEmail() == $dtoB->getEmail()
                && $dtoA->isPrimary() === $dtoB->isPrimary()
            )
        );
    }

    public static function fromArray($data)
    {
        TypeCheck::assertArray($data);

        $dto = new self;
        $dto
            ->setId(ArrayUtils::tryGet($data, 'id'))
            ->setEmail($data['email'])
            ->setIsPrimary(!empty($data['isPrimary']));

        return $dto;
    }

    public function toArray()
    {
        return [
            'id'        => $this->getId(),
            'email'     => $this->getEmail(),
            'isPrimary' => $this->isPrimary(),
        ];
    }
}
