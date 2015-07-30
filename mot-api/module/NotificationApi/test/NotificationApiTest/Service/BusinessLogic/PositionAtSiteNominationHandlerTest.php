<?php

namespace NotificationApiTest\Service\BusinessLogic;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\MockHandler;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\NotificationActionLookup;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteBusinessRole;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use NotificationApi\Service\BusinessLogic\PositionAtSiteNominationHandler;
use NotificationApi\Service\NotificationService;
use NotificationApiTest\Entity\NotificationCreatorTrait;
use UserFacade\UserFacadeLocal;
use NotificationApi\Service\Helper\SiteNominationEventHelper;

/**
 * Unit tests for PositionAtSiteNominationHandler
 */
class PositionAtSiteNominationHandlerTest extends AbstractServiceTestCase
{
    use NotificationCreatorTrait;

    const NOMINATION_ID = 1;

    public function test_proceed_rejected_shouldRejectNomination()
    {
        $this->runTest_proceed_action(
            PositionAtSiteNominationHandler::REJECTED,
            PositionAtSiteNominationHandler::ACTION_REJECTED_ID
        );
    }

    public function test_proceed_accepted_shouldAcceptNomination()
    {
        $this->runTest_proceed_action(
            PositionAtSiteNominationHandler::ACCEPTED,
            PositionAtSiteNominationHandler::ACTION_ACCEPTED_ID
        );
    }

    private function runTest_proceed_action($action, $lookupActionId)
    {
        $this->markTestSkipped();
        $handler = $this->createPositionAtSideHandler(
            $action,
            $lookupActionId
        );
        $notification = $this->createNotificationWithFields(
            [
                PositionAtSiteNominationHandler::NOMINATION_ID => self::NOMINATION_ID,
                PositionAtSiteNominationHandler::NOMINATOR_ID  => 1,
                PositionAtSiteNominationHandler::SITE_NAME     => 'Test Garage',
                PositionAtSiteNominationHandler::POSITION_NAME => 'tester',
                PositionAtSiteNominationHandler::ROLE          => SiteBusinessRoleCode::TESTER
            ]
        );
        $handler->proceed($notification);
    }

    private function createPositionAtSideHandler($action, $notificationActionId)
    {
        $entityManagerMock = $this->getMockWithDisabledConstructor(EntityManager::class);
        $mockHandler = new MockHandler($entityManagerMock, $this);

        $mockHandler->find()
            ->with(SiteBusinessRoleMap::class, self::NOMINATION_ID)
            ->will($this->returnValue($this->createSitePositionEntity()));

        $mockHandler->find()
            ->with(NotificationActionLookup::class, $notificationActionId)
            ->will($this->returnValue(new NotificationActionLookup()));

        return new PositionAtSiteNominationHandler(
            XMock::of(EventService::class),
            $entityManagerMock,
            $this->getMockWithDisabledConstructor(NotificationService::class),
            XMock::of(UserFacadeLocal::class),
            $action,
            XMock::of(SiteNominationEventHelper::class)
        );
    }

    private static function createSitePositionEntity()
    {
        $site = new Site();
        $siteRole = new SiteBusinessRole();
        $person = new Person();

        $map = new SiteBusinessRoleMap();
        $map->setPerson($person)
            ->setSiteBusinessRole($siteRole)
            ->setSite($site);
        return $map;
    }
}
