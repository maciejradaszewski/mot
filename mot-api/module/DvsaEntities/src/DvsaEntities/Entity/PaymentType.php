<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * Payment
 *
 * @ORM\Table(name="payment_type", options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"})
 * @ORM\Entity(readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="staticdata")
 */
class PaymentType extends Entity
{

    use CommonIdentityTrait;

    /**
     * @var float
     *
     * @ORM\Column(name="type_name", type="string", length=75, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="display_order", type="smallint", nullable=false)
     */
    private $displayOrder;

    /**
     * Set name
     *
     * @param string $name
     * @return \DvsaEntities\Entity\PaymentType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param bool $active
     * @return \DvsaEntities\Entity\PaymentType
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set display order
     *
     * @param int $displayOrder
     * @return \DvsaEntities\Entity\PaymentType
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get display order
     *
     * @return int
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }
}
