<?php

namespace SiteApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AbstractService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\SiteContact;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Repository;
use DvsaEntities\Repository\SiteContactRepository;
use DvsaEntities\Repository\SiteContactTypeRepository;

class SiteContactService extends AbstractService
{
    /**  @var \DvsaCommonApi\Filter\XssFilter */
    protected $xssFilter;
    /** @var ContactDetailsService  */
    private $contactDetailsService;
    /** @var UpdateVtsAssertion  */
    private $updateVtsAssertion;
    /** @var SiteContactRepository  */
    private $siteContactRepo;
    /** @var SiteContactTypeRepository  */
    private $siteContactTypeRepo;

    public function __construct(
        EntityManager $entityManager,
        ContactDetailsService $contactDetailsService,
        XssFilter $xssFilter,
        UpdateVtsAssertion $updateVtsAssertion
    ) {
        parent::__construct($entityManager);

        $this->contactDetailsService = $contactDetailsService;
        $this->xssFilter = $xssFilter;
        $this->updateVtsAssertion = $updateVtsAssertion;

        $this->siteContactRepo = $entityManager->getRepository(SiteContact::class);
        $this->siteContactTypeRepo = $entityManager->getRepository(SiteContactType::class);
    }

    /**
     * Update site contact from Dto
     *
     * @param integer        $siteId
     * @param SiteContactDto $dto
     *
     * @return array
     * @throws NotFoundException
     */
    public function updateContactFromDto($siteId, SiteContactDto $dto)
    {
        if ($dto->getType() === SiteContactTypeCode::BUSINESS) {
            $this->updateVtsAssertion->assertUpdateBusinessDetails($siteId);
        }

        $contact = $this->siteContactRepo->getHydratedByTypeCode($siteId, $dto->getType());

        //  --  Xss filter  --
        $dto = $this->xssFilter->filter($dto);

        //  --  update contact   --
        $contactDetails = $contact->getDetails();
        $contactDetails = $this->contactDetailsService->setContactDetailsFromDto($dto, $contactDetails);

        //  --  save --
        $this->siteContactRepo->save($contactDetails);

        return ['id' => $contact->getId()];
    }
}
