<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * Entity that holds information about odometer reading gathered during mot test
 *
 * @ORM\Table(name="odometer_reading", options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"})
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\OdometerReadingRepository")
 */
class OdometerReading extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @var string|null
     * @ORM\Column
     */
    private $unit;

    /**
     * @var string|null
     * @ORM\Column(name="result_type", type="string", length=10, nullable=true)
     */
    private $resultType;

    /**
     * √* @return OdometerReading
     */
    public static function create()
    {
        return new OdometerReading();
    }

    /**
     * @return $this
     */
    public function newCopy()
    {
        $currentClone = clone $this;
        return $currentClone->setId(null);
    }

    //region Setters and Getters
    /**
     * @return null|string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param null|string $v
     *
     * @return $this
     */
    public function setUnit($v)
    {
        $this->unit = $v;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int|null $v
     *
     * @return $this
     */
    public function setValue($v)
    {
        $this->value = $v;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getResultType()
    {
        return $this->resultType;
    }

    /**
     * @param int|null $v
     *
     * @return $this
     */
    public function setResultType($v)
    {
        $this->resultType = $v;
        return $this;
    }

    //endregion
}
