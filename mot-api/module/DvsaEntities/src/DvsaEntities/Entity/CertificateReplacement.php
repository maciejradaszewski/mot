<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * CertificateReplacement
 *
 * @ORM\Table(name="certificate_replacement")
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\CertificateReplacementRepository")
 */
class CertificateReplacement extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="mot_test_version", type="integer", nullable=false)
     */
    private $motTestVersion;

    /**
     * @var \DvsaEntities\Entity\MotTest
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\MotTest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mot_test_id", referencedColumnName="id")
     * })
     */
    private $motTest;

    /**
     * @var \DvsaEntities\Entity\CertificateChangeDifferentTesterReason
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\CertificateChangeDifferentTesterReason")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="different_tester_reason_id", referencedColumnName="id")
     * })
     */
    private $differentTesterReason;

    /**
     * @var \DvsaEntities\Entity\CertificateType
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\CertificateType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="certificate_type_id", referencedColumnName="id")
     * })
     */
    private $certificateType;

    /**
     * @var string $replacementReason
     *
     * @ORM\Column(name="reason", type="string", nullable=true)
     */
    private $replacementReason;

    /**
     * @var string
     *
     * @ORM\Column(name="is_vin_vrm_expiry_changed", type="integer", length=1, nullable=true)
     */
    private $isVinVrmExpiryChanged;

    /**
     * @param \DvsaEntities\Entity\MotTest $motTest
     *
     * @return $this
     */
    public function setMotTest($motTest)
    {
        $this->motTest = $motTest;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\MotTest
     */
    public function getMotTest()
    {
        return $this->motTest;
    }

    /**
     * @param int $motTestVersion
     *
     * @return $this
     */
    public function setMotTestVersion($motTestVersion)
    {
        $this->motTestVersion = $motTestVersion;
        return $this;
    }

    /**
     * @return int
     */
    public function getMotTestVersion()
    {
        return $this->motTestVersion;
    }

    /**
     * @param CertificateType $certificateType
     * @return $this
     */
    public function setCertificateType($certificateType)
    {
        $this->certificateType = $certificateType;
        return $this;
    }

    /**
     * @return CertificateType
     */
    public function getCertificateType()
    {
        return $this->certificateType;
    }

    /**
     * @param string $replacementReason
     *
     * @return $this
     */
    public function setReplacementReason($replacementReason)
    {
        $this->replacementReason = $replacementReason;
        return $this;
    }

    /**
     * @return string
     */
    public function getReplacementReason()
    {
        return $this->replacementReason;
    }

    /**
     * @param \DvsaEntities\Entity\CertificateChangeDifferentTesterReason $differentTesterReason
     *
     * @return $this
     */
    public function setReasonForDifferentTester($differentTesterReason)
    {
        $this->differentTesterReason = $differentTesterReason;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\CertificateChangeDifferentTesterReason
     */
    public function getReasonForDifferentTester()
    {
        return $this->differentTesterReason;
    }

    /**
     * @param integer $isVinVrmExpiryChanged
     *
     * @return $this
     */
    public function setIsVinVrmExpiryChanged($isVinVrmExpiryChanged)
    {
        $this->isVinVrmExpiryChanged = $isVinVrmExpiryChanged;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIsVinVrmExpiryChanged()
    {
        return $this->isVinVrmExpiryChanged;
    }
}
