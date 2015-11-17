<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestValidDto implements ReflectiveDtoInterface
{
    public $propertyWithoutASetter;

    private $propertyWithoutAGetter;

    private $scalarProperty;

    private $nestedDtoWithConstraint;

    private $nestedDtoWithDoc;

    private $nestedDtoWithDocAndConstraint;

    private $nestedConvertibleWithConstraint;

    private $nestedConvertibleWithDoc;

    private $nestedConvertibleWithDocAndConstraint;

    private $propertyWithGet;

    private $propertyWithHas;

    private $propertyWithIs;

    private $nestedDtoArray;

    private $nestedConvertibleArray;

    private $scalarArray;

    private $unknownArray;

    public function getPropertyWithoutASetter()
    {
        return $this->propertyWithoutASetter;
    }

    public function setPropertyWithoutAGetter($propertyWithoutAGetter)
    {
        $this->propertyWithoutAGetter = $propertyWithoutAGetter;
    }

    public function getScalarProperty()
    {
        return $this->scalarProperty;
    }

    /**
     * @param int $scalarProperty
     */
    public function setScalarProperty($scalarProperty)
    {
        $this->scalarProperty = $scalarProperty;
    }

    public function getNestedDtoWithConstraint()
    {
        return $this->nestedDtoWithConstraint;
    }

    public function setNestedDtoWithConstraint(TestNestedDto $nestedDtoWithConstraint)
    {
        $this->nestedDtoWithConstraint = $nestedDtoWithConstraint;
    }

    public function getNestedDtoWithDoc()
    {
        return $this->nestedDtoWithDoc;
    }

    /**
     * @param \DvsaCommonTest\DtoSerialization\TestDto\TestNestedDto $nestedDtoWithDoc
     */
    public function setNestedDtoWithDoc($nestedDtoWithDoc)
    {
        $this->nestedDtoWithDoc = $nestedDtoWithDoc;
    }

    public function getNestedDtoWithDocAndConstraint()
    {
        return $this->nestedDtoWithDocAndConstraint;
    }

    /**
     * @param \DvsaCommonTest\DtoSerialization\TestDto\TestNestedDto $nestedDtoWithDocAndConstraint
     */
    public function setNestedDtoWithDocAndConstraint(TestNestedDto $nestedDtoWithDocAndConstraint)
    {
        $this->nestedDtoWithDocAndConstraint = $nestedDtoWithDocAndConstraint;
    }

    public function getNestedConvertibleWithConstraint()
    {
        return $this->nestedConvertibleWithConstraint;
    }

    public function setNestedConvertibleWithConstraint(\DateTime $nestedConvertibleWithConstraint)
    {
        $this->nestedConvertibleWithConstraint = $nestedConvertibleWithConstraint;
    }

    public function getNestedConvertibleWithDoc()
    {
        return $this->nestedConvertibleWithDoc;
    }

    /**
     * @param \DateTime $nestedConvertibleWithDoc
     */
    public function setNestedConvertibleWithDoc($nestedConvertibleWithDoc)
    {
        $this->nestedConvertibleWithDoc = $nestedConvertibleWithDoc;
    }

    public function getNestedConvertibleWithDocAndConstraint()
    {
        return $this->nestedConvertibleWithDocAndConstraint;
    }

    /**
     * @param \DateTime $nestedConvertibleWithDocAndConstraint
     */
    public function setNestedConvertibleWithDocAndConstraint(\DateTime $nestedConvertibleWithDocAndConstraint)
    {
        $this->nestedConvertibleWithDocAndConstraint = $nestedConvertibleWithDocAndConstraint;
    }

    public function getPropertyWithGet()
    {
        return $this->propertyWithGet;
    }

    public function setPropertyWithGet($propertyWithGet)
    {
        $this->propertyWithGet = $propertyWithGet;
    }

    public function hasPropertyWithHas()
    {
        return $this->propertyWithHas;
    }

    public function setPropertyWithHas($propertyWithHas)
    {
        $this->propertyWithHas = $propertyWithHas;
    }

    public function isPropertyWithIs()
    {
        return $this->propertyWithIs;
    }

    public function setPropertyWithIs($propertyWithIs)
    {
        $this->propertyWithIs = $propertyWithIs;
    }

    public function getNestedDtoArray()
    {
        return $this->nestedDtoArray;
    }

    /**
     * @param \DvsaCommonTest\DtoSerialization\TestDto\TestNestedDto[] $nestedDtoArray
     */
    public function setNestedDtoArray(array $nestedDtoArray)
    {
        $this->nestedDtoArray = $nestedDtoArray;
    }

    public function getNestedConvertibleArray()
    {
        return $this->nestedConvertibleArray;
    }

    /**
     * @param \DvsaCommon\Date\Time[] $nestedConvertibleArray
     */
    public function setNestedConvertibleArray(array $nestedConvertibleArray)
    {
        $this->nestedConvertibleArray = $nestedConvertibleArray;
    }

    public function getScalarArray()
    {
        return $this->scalarArray;
    }

    /**
     * @param int[] $scalarArray
     */
    public function setScalarArray(array $scalarArray)
    {
        $this->scalarArray = $scalarArray;
    }

    public function getUnknownArray()
    {
        return $this->unknownArray;
    }

    public function setUnknownArray(array $unknownArray)
    {
        $this->unknownArray = $unknownArray;
    }
}
