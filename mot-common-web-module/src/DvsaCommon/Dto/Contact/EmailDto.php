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
    /** @var  boolean */
    private $isPrimary;

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
        $this->isPrimary = $isPrimary;
        return $this;
    }

    public function getIsPrimary()
    {
        return $this->isPrimary;
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
                && $dtoA->getIsPrimary() === $dtoB->getIsPrimary()
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
            'isPrimary' => $this->getIsPrimary(),
        ];
    }

}
