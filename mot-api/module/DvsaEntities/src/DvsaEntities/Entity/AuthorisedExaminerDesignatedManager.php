<?php
namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * AuthorisedExaminerDesignatedManager
 *
 * @ORM\Table(name="application_authorised_examiner_designated_manager", options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={@ORM\Index(name="fk_designated_manager_contact_details", columns={"contact_details_id"})})
 * @ORM\Entity
 */
class AuthorisedExaminerDesignatedManager
{
    use CommonIdentityTrait;

    const ENTITY_NAME = 'Authorised Examiner Designated Manager';

    /**
     * @var string
     *
     * @ORM\Column(name="user_identification_number", type="string", length=20, nullable=true)
     */
    private $userIdentificationNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mot_scheme_trained_person", type="boolean", nullable=false)
     */
    private $motSchemeTrainedPerson;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mot_managers_course", type="boolean", nullable=false)
     */
    private $motManagersCourse;

    /**
     * @var boolean
     *
     * @ORM\Column(name="another_role", type="boolean", nullable=false)
     */
    private $anotherRole;

    /**
     * @var \DvsaEntities\Entity\ContactDetail
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\ContactDetail", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contact_details_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $contactDetails;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_application_authorised_examiner_principal", type="boolean", nullable=true)
     */
    private $isAuthorisedExaminerPrincipal;

    /**
     * Set userIdentificationNumber
     *
     * @param string $userIdentificationNumber
     * @return AuthorisedExaminerDesignatedManager
     */
    public function setUserIdentificationNumber($userIdentificationNumber)
    {
        $this->userIdentificationNumber = $userIdentificationNumber;

        return $this;
    }

    /**
     * Get userIdentificationNumber
     *
     * @return string
     */
    public function getUserIdentificationNumber()
    {
        return $this->userIdentificationNumber;
    }

    /**
     * Set motSchemeTrainedPerson
     *
     * @param boolean $motSchemeTrainedPerson
     * @return AuthorisedExaminerDesignatedManager
     */
    public function setMotSchemeTrainedPerson($motSchemeTrainedPerson)
    {
        $this->motSchemeTrainedPerson = $motSchemeTrainedPerson;

        return $this;
    }

    /**
     * Get motSchemeTrainedPerson
     *
     * @return boolean
     */
    public function getMotSchemeTrainedPerson()
    {
        return $this->motSchemeTrainedPerson;
    }

    /**
     * Set motManagersCourse
     *
     * @param boolean $motManagersCourse
     * @return AuthorisedExaminerDesignatedManager
     */
    public function setMotManagersCourse($motManagersCourse)
    {
        $this->motManagersCourse = $motManagersCourse;

        return $this;
    }

    /**
     * Get motManagersCourse
     *
     * @return boolean
     */
    public function getMotManagersCourse()
    {
        return $this->motManagersCourse;
    }

    /**
     * Set anotherRole
     *
     * @param boolean $anotherRole
     * @return AuthorisedExaminerDesignatedManager
     */
    public function setAnotherRole($anotherRole)
    {
        $this->anotherRole = $anotherRole;

        return $this;
    }

    /**
     * Get anotherRole
     *
     * @return boolean
     */
    public function getAnotherRole()
    {
        return $this->anotherRole;
    }

    /**
     * Set contactDetails
     *
     * @param ContactDetails $contactDetails
     * @return AuthorisedExaminerDesignatedManager
     */
    public function setContactDetails($contactDetails)
    {
        $this->contactDetails = $contactDetails;

        return $this;
    }

    /**
     * Get contactDetailsId
     *
     * @return \DvsaEntities\Entity\ContactDetail
     */
    public function getContactDetails()
    {
        return $this->contactDetails;
    }

    /**
     * Set isAuthorisedExaminerPrincipal
     *
     * @param boolean $isAuthorisedExaminerPrincipal
     * @return AuthorisedExaminerDesignatedManager
     */
    public function setIsAuthorisedExaminerPrincipal($isAuthorisedExaminerPrincipal)
    {
        $this->isAuthorisedExaminerPrincipal = $isAuthorisedExaminerPrincipal;

        return $this;
    }

    /**
     * Get isAuthorisedExaminerPrincipal
     *
     * @return boolean
     */
    public function getIsAuthorisedExaminerPrincipal()
    {
        return $this->isAuthorisedExaminerPrincipal;
    }
}
