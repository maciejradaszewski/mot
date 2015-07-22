<?php

namespace DvsaCommon\Dto\Contact;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;

/**
 * Class PhoneDto
 *
 * @package DvsaCommon\Dto\Contact
 */
class PhoneDto extends AbstractDataTransferObject
{
    private $id;
    private $isPrimary;
    private $number;
    /** @var string phone contact type code */
    private $contactType;

    public function setContactType($contactType)
    {
        $this->contactType = $contactType;
        return $this;
    }

    public function getContactType()
    {
        return $this->contactType;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
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

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param PhoneDto $dtoA
     * @param PhoneDto $dtoB
     *
     * @return bool
     */
    public static function isEquals($dtoA, $dtoB)
    {
        return (
            ($dtoA === null && $dtoB === null)
            || (
                $dtoA instanceof PhoneDto
                && $dtoB instanceof PhoneDto
                && $dtoA->getNumber() == $dtoB->getNumber()
                && $dtoA->getContactType() === $dtoB->getContactType()
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
            ->setNumber($data['number'])
            ->setIsPrimary(!empty($data['isPrimary']))
            ->setContactType($data['type']);

        return $dto;
    }

    public function toArray()
    {
        return [
            'id'        => $this->getId(),
            'number'    => $this->getNumber(),
            'isPrimary' => $this->isPrimary(),
            'type'      => $this->getContactType(),
        ];
    }

    public function isEmpty()
    {
        return self::isEquals($this, new PhoneDto());
    }
}
