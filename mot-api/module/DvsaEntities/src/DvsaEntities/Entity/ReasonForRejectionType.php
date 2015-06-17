<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReasonForRejectionType
 *
 * @ORM\Table(name="reason_for_rejection_type")
 * @ORM\Entity
 */
class ReasonForRejectionType
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reasonForRejectionType;

    /**
     * Get reasonForRejectionType
     *
     * @return string
     */
    public function getReasonForRejectionType()
    {
        return $this->reasonForRejectionType;
    }
}
