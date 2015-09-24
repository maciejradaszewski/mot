<?php

namespace SiteApi\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use NotificationApi\Dto\Notification;
use NotificationApi\Service\NotificationService;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommon\Constants\EventDescription;
use DvsaEntities\Entity\EventPersonMap;
use DvsaEntities\Entity\EventSiteMap;
use DvsaEventApi\Service\EventService;
use DvsaEntities\Repository\SiteBusinessRoleMapRepository;

/**
 * Takes care of operations associated with site position
 */
class SitePositionService implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    private $entityManager;
    private $notificationService;
    /** @var  EventService $eventService */
    private $eventService;


    /**
     * @var  AbstractMotAuthorisationService $authorisationService
     */
    private $authorisationService;

    public function __construct(
        EventService $eventService,
        SiteBusinessRoleMapRepository $siteBusinessRoleMapRepository,
        AuthorisationServiceInterface $authorisationService,
        EntityManager $entityManager,
        NotificationService $notificationService
    ) {
        $this->eventService = $eventService;
        $this->siteBusinessRoleMapRepository = $siteBusinessRoleMapRepository;
        $this->authorisationService = $authorisationService;
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
    }

    /**
     * Removes role in a site
     * + TODO: asserts operation possible for the current user
     * + asserts valid SiteBusinessRoleMap
     * + removes physical SiteBusinessRoleMap entity
     * + adds SitePositionHistory entity in REMOVED status
     * + sends removal notification
     *
     * @param int $siteId
     * @param int $mapId
     *
     * @throws \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function remove($siteId, $mapId)
    {
        /** @var SiteBusinessRoleMap $siteRoleMap */
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::NOMINATE_ROLE_AT_SITE, $siteId);

        $siteRoleMap = $this->siteBusinessRoleMapRepository->findOneBy([ 'id' => $mapId ]);

        if ($siteRoleMap) {
            $this->assertValidPositionInSite($siteRoleMap, $siteId);

            $this->sendRemovalNotification($siteRoleMap);
            $this->submitEvent($siteRoleMap);

            $this->entityManager->remove($siteRoleMap);
            $this->entityManager->flush();
        }
    }

    private function sendRemovalNotification(SiteBusinessRoleMap $roleMap)
    {
        $removalNotification = (new Notification())->setTemplate(Notification::TEMPLATE_SITE_POSITION_REMOVED)
            ->setRecipient($roleMap->getPerson()->getId())
            ->addField("positionName", $roleMap->getSiteBusinessRole()->getName())
            ->addField("siteName", $roleMap->getSite()->getName())
            ->addField("siteOrOrganisationId", $roleMap->getSite()->getSiteNumber())
            ->toArray();

        $this->notificationService->add($removalNotification);
    }

    private function assertValidPositionInSite(SiteBusinessRoleMap $map, $siteId)
    {
        if ($map->getSite()->getId() !== $siteId) {
            throw new BadRequestException(
                "Invalid relation between site and role map",
                BadRequestException::ERROR_CODE_BUSINESS_FAILURE
            );
        }
    }

    private function submitEvent(SiteBusinessRoleMap $siteRoleMap)
    {
        // Person Level
        $event = $this->eventService->addEvent(
            EventTypeCode::ROLE_ASSOCIATION_CHANGE,
            sprintf(
                EventDescription::ROLE_ASSOCIATION_REMOVE,
                $siteRoleMap->getSiteBusinessRole()->getName(),
                $siteRoleMap->getSite()->getName(),
                $siteRoleMap->getSite()->getSiteNumber()
            ),
            new \DateTime()
        );

        $eventPersonMap = new EventPersonMap();
        $eventPersonMap->setEvent($event)
                       ->setPerson($siteRoleMap->getPerson());

        $this->entityManager->persist($eventPersonMap);

        // Site Level
        $event = $this->eventService->addEvent(
            EventTypeCode::ROLE_ASSOCIATION_CHANGE,
            sprintf(
                EventDescription::ROLE_ASSOCIATION_REMOVE_SITE_ORG,
                $siteRoleMap->getSiteBusinessRole()->getName(),
                $siteRoleMap->getPerson()->getDisplayName(),
                $siteRoleMap->getSite()->getName(),
                $siteRoleMap->getSite()->getSiteNumber()
            ),
            new \DateTime()
        );

        $eventSiteMap = new EventSiteMap();
        $eventSiteMap->setEvent($event)
                     ->setSite($siteRoleMap->getSite());

        $this->entityManager->persist($eventSiteMap);
        $this->entityManager->flush();
    }

}
