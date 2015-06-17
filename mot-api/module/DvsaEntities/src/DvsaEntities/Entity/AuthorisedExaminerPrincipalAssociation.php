<?php
namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * AuthorisedExaminerPrincipal
 *
 * @ORM\Table(name="auth_for_ae_person_as_principal_map")
 * @ORM\Entity
 */
class AuthorisedExaminerPrincipalAssociation extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var AuthorisationForAuthorisedExaminer
     *
     * @ORM\ManyToOne(targetEntity="\DvsaEntities\Entity\AuthorisationForAuthorisedExaminer", fetch="EAGER", inversedBy="authorisedExaminersPrincipalAssociations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="auth_for_ae_id", referencedColumnName="id")
     * })
     */
    private $authorisedExaminer;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="\DvsaEntities\Entity\Person", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * })
     */
    private $person;

    /**
     * @return \DvsaEntities\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @return \DvsaEntities\Entity\AuthorisationForAuthorisedExaminer
     */
    public function getAuthorisedExaminer()
    {
        return $this->authorisedExaminer;
    }

    public function __construct(Person $person, AuthorisationForAuthorisedExaminer $authorisedExaminer)
    {
        $this->person = $person;
        $this->authorisedExaminer = $authorisedExaminer;
    }
}
