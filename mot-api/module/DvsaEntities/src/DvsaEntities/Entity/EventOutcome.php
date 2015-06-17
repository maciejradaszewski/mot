<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * EventOutcome
 *
 * @ORM\Table(
 * name="event_outcome_lookup",
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uk_code", columns={"code"}),
 *      @ORM\UniqueConstraint(name="uk_display_order", columns={"display_order"})
 *  },
 *  indexes={
 *      @ORM\Index(name="ix_created_by", columns={"created_by"}),
 *      @ORM\Index(name="ix_last_updated_by", columns={"last_updated_by"})
 *  }
 * )
 * @ORM\Entity
 */
class EventOutcome extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="display_order", type="smallint", nullable=false)
     */
    private $displayOrder;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return EventOutcome
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return EventOutcome
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * @param int $displayOrder
     *
     * @return EventOutcome
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }
}
