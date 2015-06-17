<?php

namespace DvsaAuthorisation\Service;

use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaEntities\Entity\Address;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\OrganisationContact;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use DvsaEntities\Entity\SiteContact;
use DvsaEntities\Repository\OrganisationBusinessRoleMapRepository;
use DvsaEntities\Repository\PersonSystemRoleMapRepository;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\SiteBusinessRoleMapRepository;

/**
 * List user roles.
 */
class UserRoleService
{
    private $organisationRoleMapRepository;
    private $siteRoleMapRepository;
    private $personSystemRoleMapRepository;

    /**
     * @param OrganisationBusinessRoleMapRepository $organisationRoleMapRepository
     * @param SiteBusinessRoleMapRepository $siteRoleMapRepository
     * @param PersonSystemRoleMapRepository $personSystemRoleMapRepository
     */
    public function __construct(
        $organisationRoleMapRepository,
        $siteRoleMapRepository,
        $personSystemRoleMapRepository
    ) {
        $this->organisationRoleMapRepository = $organisationRoleMapRepository;
        $this->siteRoleMapRepository = $siteRoleMapRepository;
        $this->personSystemRoleMapRepository = $personSystemRoleMapRepository;
    }

    /**
     * @param Person $person
     * @return array [
     *  'system' => string[]
     *   'organisations' => [
     *      'name' => string,
     *      'address' => string,
     *      'roles' => string[],
     *   'sites' => ...
     * ]
     */
    public function getDetailedRolesForPerson(Person $person)
    {
        return [
            'system' => $this->getSystemRoles($person->getId()),
            'organisations' => $this->getOrganisationRoles($person->getId()),
            'sites' => $this->getSiteRoles($person->getId()),
        ];
    }

    private function getSystemRoles($personId)
    {
        $roles = $this->personSystemRoleMapRepository->getActiveUserRoles($personId);

        $systemRoles = [];
        foreach ($roles as $role) {
            $systemRoles[] = $role->getPersonSystemRole()->getName();
        }

        return ['roles' => $systemRoles];
    }

    private function getOrganisationRoles($personId)
    {
        /** @var OrganisationBusinessRoleMap[] $roles */
        $roles = $this->organisationRoleMapRepository->getActiveUserRoles($personId);

        $organisationRoles = [];

        foreach ($roles as $role) {
            $organisation = $role->getOrganisation();
            if (!array_key_exists($organisation->getId(), $organisationRoles)) {
                $organisationRoles[$organisation->getId()] = [
                    'name' => $organisation->getName(),
                    'number' => $organisation->getRegisteredCompanyNumber(),
                    'address' => $this->mapOrganisationContactsToAddress($organisation->getContacts()),
                    'roles' => [],
                ];
            }

            $organisationRoles[$organisation->getId()]['roles'][]
                = $role->getOrganisationBusinessRole()->getShortName();
        }

        return $organisationRoles;
    }

    /**
     * @param $organisationContacts OrganisationContact[]
     * @return string
     */
    private function mapOrganisationContactsToAddress($organisationContacts)
    {
        foreach ($organisationContacts as $contact) {
            if ($contact->getType()->getCode() === OrganisationContactTypeCode::REGISTERED_COMPANY) {
                return $this->getAddressAsString($contact->getDetails()->getAddress());
            }
        }
        return "";
    }

    /**
     * @param Address $address
     * @return string
     */
    private function getAddressAsString($address)
    {
        return join(
            ', ',
            array_filter(
                [
                    $address->getAddressLine1(),
                    $address->getAddressLine2(),
                    $address->getAddressLine3(),
                    $address->getAddressLine4(),
                    $address->getTown(),
                    $address->getPostcode(),
                ]
            )
        );
    }

    private function getSiteRoles($personId)
    {
        /** @var SiteBusinessRoleMap[] $roles */
        $roles = $this->siteRoleMapRepository->getActiveUserRoles($personId);

        $siteRoles = [];

        foreach ($roles as $role) {
            $site = $role->getSite();
            if (!array_key_exists($site->getId(), $siteRoles)) {
                $siteRoles[$site->getId()] = [
                    'name' => $site->getName(),
                    'number' => $site->getSiteNumber(),
                    'address' => $this->mapSiteContactsToAddress($site->getContacts()),
                    'roles' => [],
                ];
            }

            $siteRoles[$site->getId()]['roles'][]
                = $role->getSiteBusinessRole()->getCode();
        }

        return $siteRoles;
    }

    /**
     * @param SiteContact[] $siteContacts
     * @return \DvsaCommon\Dto\Contact\AddressDto|null
     */
    private function mapSiteContactsToAddress($siteContacts)
    {
        foreach ($siteContacts as $contact) {
            if ($contact->getType()->getCode() === SiteContactTypeCode::BUSINESS) {
                return $this->getAddressAsString($contact->getDetails()->getAddress());
            }
        }
        return "";
    }
}
