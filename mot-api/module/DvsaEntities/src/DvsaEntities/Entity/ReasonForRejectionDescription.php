<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * Reasons for rejection translated texts.
 * @ORM\Table(name="rfr_language_content_map")
 * @ORM\Entity()
 */
class ReasonForRejectionDescription extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var ReasonForRejection
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\ReasonForRejection", inversedBy="descriptions")
     * @ORM\JoinColumn(name="rfr_id", referencedColumnName="id")
     */
    private $reasonForRejection;

    /**
     * @var Language
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Language", fetch="EAGER")
     * @ORM\JoinColumn(name="language_type_id", referencedColumnName="id")
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="inspection_manual_description", type="string", length=500, nullable=true)
     */
    private $inspectionManualDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="advisory_text", type="string", length=250, nullable=true)
     */
    private $advisoryText;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdvisoryText()
    {
        return $this->advisoryText;
    }

    /**
     * @param string $advisoryText
     * @return $this
     */
    public function setAdvisoryText($advisoryText)
    {
        $this->advisoryText = $advisoryText;
        return $this;
    }

    /**
     * @return string
     */
    public function getInspectionManualDescription()
    {
        return $this->inspectionManualDescription;
    }

    /**
     * @param string $inspectionManualDescription
     * @return $this
     */
    public function setInspectionManualDescription($inspectionManualDescription)
    {
        $this->inspectionManualDescription = $inspectionManualDescription;
        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param Language $language
     * @return $this
     */
    public function setLanguage(Language $language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return ReasonForRejection
     */
    public function getReasonForRejection()
    {
        return $this->reasonForRejection;
    }
}
