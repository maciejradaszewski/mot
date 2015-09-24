<?php
namespace OrganisationApi\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaCommon\Auth\Assertion\RemovePositionAtAeAssertion;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaEntities\Entity\EventOrganisationMap;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Repository\OrganisationPositionHistoryRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEventApi\Service\EventService;
use NotificationApi\Dto\Notification;
use NotificationApi\Service\NotificationService;
use OrganisationApi\Service\Mapper\OrganisationPositionMapper;
use DvsaEntities\Repository\OrganisationBusinessRoleMapRepository;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommon\Constants\EventDescription;
use DvsaEntities\Entity\EventSiteMap;
use DvsaEntities\Entity\EventPersonMap;

/**
 * Class OrganisationPositionService
 */
class OrganisationPositionService implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    private $organisationRepository;
    private $organisationBusinessRoleMapRepository;
    private $organisationPositionHistoryRepository;
    private $positionMapper;
    private $notificationService;
    /** @var MotIdentityProviderInterface $motIdentityProvider */
    private $motIdentityProvider;
    private $authorisationService;
    private $entityManager;
    private $eventService;

    public function __construct(
        OrganisationRepository $organisationRepository,
        OrganisationBusinessRoleMapRepository $organisationBusinessRoleMapRepository,
        OrganisationPositionHistoryRepository $organisationPositionHistoryRepository,
        OrganisationPositionMapper $positionMapper,
        NotificationService $notificationService,
        MotIdentityProviderInterface $motIdentityProvider,
        MotAuthorisationServiceInterface $authorisationService,
        EntityManager $entityManager,
        EventService $eventService
    ) {
        $this->organisationRepository                = $organisationRepository;
        $this->organisationBusinessRoleMapRepository = $organisationBusinessRoleMapRepository;
        $this->organisationPositionHistoryRepository = $organisationPositionHistoryRepository;
        $this->positionMapper                        = $positionMapper;
        $this->notificationService                   = $notificationService;
        $this->motIdentityProvider                   = $motIdentityProvider;
        $this->authorisationService                  = $authorisationService;
        $this->entityManager                         = $entityManager;
        $this->eventService                          = $eventService;
    }

    public function getListForOrganisation($organisationId)
    {
        $this->authorisationService->assertGrantedAtOrganisation(
            PermissionAtOrganisation::LIST_AE_POSITIONS, $organisationId
        );

        $organisation = $this->organisationRepository->get($organisationId);

        $positions = ArrayUtils::filter($organisation->getPositions(),
            function (OrganisationBusinessRoleMap $position) {
                return $position->getBusinessRoleStatus()->getCode() == BusinessRoleStatusCode::ACTIVE
                    || $position->getBusinessRoleStatus()->getCode() == BusinessRoleStatusCode::PENDING;
            }
        );

        return $this->positionMapper->manyToDto($positions);
    }

    /**
     * Removes role in an organisation:
     * + TODO: asserts operation possible for the current user
     * + asserts valid OrganisationBusinessRoleMap
     * + removes physical OrganisationBusinessRoleMap entity
     * + adds OrganisationPositionHistory entity in REMOVED status
     * + sends removal notification
     *
     * @param int $organisationId
     * @param int $positionId
     *
     * @throws \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function remove($organisationId, $positionId)
    {
        $position = $this->organisationBusinessRoleMapRepository->get($positionId);

        $this->assertCanRemovePosition($position);
        $this->assertValidPositionInOrganisation($position, $organisationId);
        $this->submitEvent($position);
        $this->sendRemovalNotification($position);

        $this->entityManager->remove($position);
        $this->entityManager->flush();
    }

    private function sendRemovalNotification(OrganisationBusinessRoleMap $map)
    {
        $removalNotification = (new Notification())->setTemplate(Notification::TEMPLATE_ORGANISATION_POSITION_REMOVED)
            ->setRecipient($map->getPerson()->getId())
            ->addField("positionName", $map->getOrganisationBusinessRole()->getFullName())
            ->addField("organisationName", $map->getOrganisation()->getName())
            ->addField("siteOrOrganisationId", $map->getOrganisation()->getAuthorisedExaminer()->getNumber())
            ->toArray();

        $this->notificationService->add($removalNotification);
    }

    private function assertValidPositionInOrganisation(OrganisationBusinessRoleMap $position, $organisationId)
    {
        if ($position->getOrganisation()->getId() !== $organisationId) {
            throw new NotFoundException(
                "Invalid relation between organisation and position"
            );
        }
    }

    /**
     * @param  OrganisationBusinessRoleMap $position
     * @throws UnauthorisedException
     */
    private function assertCanRemovePosition($position)
    {
        if (!$position) {
            throw new \InvalidArgumentException('OrganisationBusinessRoleMap Position Not Specified');
        }

        $assertion = new RemovePositionAtAeAssertion($this->authorisationService, $this->motIdentityProvider);

        $assertion->assertGranted(
            $position->getOrganisationBusinessRole()->getName(),
            $position->getPerson()->getId(),
            $position->getOrganisation()->getId()
        );
    }

    private function submitEvent(OrganisationBusinessRoleMap $orgRoleMap)
    {
        // Person Level
        $event = $this->eventService->addEvent(
            EventTypeCode::ROLE_ASSOCIATION_CHANGE,
            sprintf(
                EventDescription::ROLE_ASSOCIATION_REMOVE,
                $orgRoleMap->getOrganisationBusinessRole()->getFullName(),
                $orgRoleMap->getOrganisation()->getName(),
                $orgRoleMap->getOrganisation()->getAuthorisedExaminer()->getNumber()
            ),
            new \DateTime()
        );

        $eventPersonMap = new EventPersonMap();
        $eventPersonMap->setEvent($event)
                       ->setPerson($orgRoleMap->getPerson());

        $this->entityManager->persist($eventPersonMap);

        // Organisation Level
        $event = $this->eventService->addEvent(
            EventTypeCode::ROLE_ASSOCIATION_CHANGE,
            sprintf(
                EventDescription::ROLE_ASSOCIATION_REMOVE_SITE_ORG,
                $orgRoleMap->getOrganisationBusinessRole()->getFullName(),
                $orgRoleMap->getPerson()->getDisplayName(),
                $orgRoleMap->getOrganisation()->getName(),
                $orgRoleMap->getOrganisation()->getAuthorisedExaminer()->getNumber()
            ),
            new \DateTime()
        );

        $eventSiteMap = new EventOrganisationMap();
        $eventSiteMap->setEvent($event)
                     ->setOrganisation($orgRoleMap->getOrganisation());

        $this->entityManager->persist($eventSiteMap);
        $this->entityManager->flush();
    }

}
