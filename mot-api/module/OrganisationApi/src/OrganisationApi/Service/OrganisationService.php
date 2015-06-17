<?php
namespace OrganisationApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Service\AbstractService;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationContact;
use DvsaEntities\Repository\OrganisationRepository;
use OrganisationApi\Service\Mapper\OrganisationMapper;
use OrganisationApi\Service\Validator\OrganisationValidator;

/**
 * Class OrganisationService
 *
 * @package OrganisationApi\Service
 */
class OrganisationService extends AbstractService
{

    private $validator;
    private $mapper;

    /**
     * @var \DvsaEntities\Repository\OrganisationRepository
     */
    private $organisationRepository;

    public function __construct(
        EntityManager $entityManager,
        OrganisationValidator $validator,
        OrganisationRepository $organisationRepository,
        OrganisationMapper $mapper
    ) {
        parent::__construct($entityManager);
        $this->validator              = $validator;
        $this->entityManager          = $entityManager;
        $this->organisationRepository = $organisationRepository;
        $this->mapper                 = $mapper;
    }

    public function persist(Organisation $organisation, array $data, OrganisationContact $organisationContact = null)
    {
        $this->validator->validate($data);

        $this->mapper->mapToObject($organisation, $data);

        if ($organisationContact) {
            $organisation->addContact($organisationContact);
        }

        $this->entityManager->persist($organisation);
        $this->entityManager->flush();

        return $organisation;
    }

    /**
     * Don't call this within a long-running transaction - it will result in
     * lock contention
     */
    public function incrementSlotBalance(Organisation $organisation)
    {
        $this->organisationRepository->updateSlotBalance($organisation->getId(), 1);
    }

    /**
     * Don't call this within a long-running transaction - it will result in
     * lock contention
     */
    public function decrementSlotBalance(Organisation $organisation)
    {
        $this->organisationRepository->updateSlotBalance($organisation->getId(), -1);
    }
}
