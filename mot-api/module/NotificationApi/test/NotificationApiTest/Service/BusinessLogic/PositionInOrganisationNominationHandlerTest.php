<?php

namespace NotificationApiTest\Service\BusinessLogic;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\MockHandler;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\NotificationActionLookup;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use NotificationApi\Service\BusinessLogic\PositionInOrganisationNominationHandler;
use NotificationApi\Service\NotificationService;
use NotificationApiTest\Entity\NotificationCreatorTrait;
use UserFacade\UserFacadeLocal;
use DvsaEventApi\Service\EventService;
use NotificationApi\Service\Helper\OrganisationNominationEventHelper;

/**
 * unit tests for PositionInOrganisationNominationHandler
 */
class PositionInOrganisationNominationHandlerTest extends AbstractServiceTestCase
{
    use NotificationCreatorTrait;

    const NOMINATION_ID = 1;

    public function test_proceed_rejected_shouldRejectNomination()
    {
        $this->runTest_proceed_action(
            PositionInOrganisationNominationHandler::REJECTED,
            PositionInOrganisationNominationHandler::ACTION_REJECTED_ID
        );
    }

    public function test_proceed_accepted_shouldAcceptNomination()
    {
        $this->runTest_proceed_action(
            PositionInOrganisationNominationHandler::ACCEPTED,
            PositionInOrganisationNominationHandler::ACTION_ACCEPTED_ID
        );
    }

    private function runTest_proceed_action($action, $lookupActionId)
    {
        $this->markTestSkipped();
        $handler = $this->createPositionInOrganisationHandler(
            $action,
            $lookupActionId
        );
        $notification = $this->createNotificationWithFields(
            [
                PositionInOrganisationNominationHandler::NOMINATION_ID     => self::NOMINATION_ID,
                PositionInOrganisationNominationHandler::NOMINATOR_ID      => 1,
                PositionInOrganisationNominationHandler::ORGANISATION_NAME => 'Test Garage',
                PositionInOrganisationNominationHandler::POSITION_NAME     => 'tester',
                PositionInOrganisationNominationHandler::ROLE              => SiteBusinessRoleCode::TESTER
            ]
        );
        $handler->proceed($notification);
    }

    private function createPositionInOrganisationHandler($action, $notificationActionId)
    {
        $entityManagerMock = $this->getMockWithDisabledConstructor(EntityManager::class);
        $mockHandler = new MockHandler($entityManagerMock, $this);

        $mockHandler->find()
            ->with(OrganisationBusinessRoleMap::class, self::NOMINATION_ID)
            ->will($this->returnValue(new OrganisationBusinessRoleMap()));

        $mockHandler->find()
            ->with(NotificationActionLookup::class, $notificationActionId)
            ->will($this->returnValue(new NotificationActionLookup()));

        return new PositionInOrganisationNominationHandler(
            XMock::of(EventService::class),
            $entityManagerMock,
            $this->getMockWithDisabledConstructor(NotificationService::class),
            XMock::of(UserFacadeLocal::class),
            $action,
            XMock::of(OrganisationNominationEventHelper::class)
        );
    }
}
