<?php
namespace OrganisationApi\Service\Mapper;

use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommonApi\Service\Mapper\AbstractApiMapper;
use DvsaEntities\Entity\Person;

/**
 * Class AuthorisedExaminerPrincipalMapper
 *
 * @package OrganisationApi\Service\Mapper
 */
class AuthorisedExaminerPrincipalMapper extends AbstractApiMapper
{
    private $personWithAddressMapper;

    public function __construct()
    {
        $this->personWithAddressMapper = new PersonWithAddressMapper();
    }

    /**
     * @param Person[] $principals
     *
     * @return PersonDto[]
     */
    public function manyToDto($principals)
    {
        return parent::manyToDto($principals);
    }

    /**
     * @param Person $principal
     *
     * @return PersonDto
     */
    public function toDto($principal)
    {
        $principalData = $this->personWithAddressMapper->toDto($principal);

        return $principalData;
    }
}
