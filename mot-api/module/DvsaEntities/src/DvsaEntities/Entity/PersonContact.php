<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaCommon\Constants\PersonContactType as PersonContactTypeEnum;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * PersonContact
 *
 * @ORM\Table(name="person_contact_detail_map", options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"})
 * @ORM\Entity
 */
class PersonContact extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var ContactDetail
     *
     * @ORM\ManyToOne(targetEntity="\DvsaEntities\Entity\ContactDetail", fetch="LAZY", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     * })
     */
    private $contactDetail;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="\DvsaEntities\Entity\Person", fetch="LAZY", inversedBy="contacts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * })
     */
    private $person;

    /**
     * @var integer
     *
     * @ORM\Column(name="contact_type_id", type="smallint", nullable=false)
     */
    private $typeId;

    public function __construct(ContactDetail $contactDetail, PersonContactTypeEnum $type, Person $person)
    {
        $this->contactDetail = $contactDetail;
        $this->typeId = $type->getId();
        $this->person = $person;
    }

    public function getDetails()
    {
        return $this->contactDetail;
    }

    /**
     * @return PersonContactTypeEnum
     */
    public function getType()
    {
        return PersonContactTypeEnum::fromId($this->typeId);
    }
}
