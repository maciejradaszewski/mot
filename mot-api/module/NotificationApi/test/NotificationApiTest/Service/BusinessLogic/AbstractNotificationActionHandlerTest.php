<?php

namespace NotificationApiTest\Service\BusinessLogic;

use Doctrine\ORM\EntityManager;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\MockHandler;
use NotificationApi\Service\BusinessLogic\AbstractNotificationActionHandler;
use NotificationApi\Service\BusinessLogic\PositionAtSiteNominationHandler;
use NotificationApi\Service\BusinessLogic\PositionInOrganisationNominationHandler;
use NotificationApi\Service\NotificationService;
use UserFacade\UserFacadeLocal;
use DvsaEventApi\Service\EventService;

/**
 * Class AbstractNotificationActionHandlerTest
 *
 * Unit testing AbstractNotificationActionHandler
 *
 * @package NotificationApiTest\Service\BusinessLogic
 */
class AbstractNotificationActionHandlerTest extends AbstractServiceTestCase
{
    private $serviceManager;

    public function setUp()
    {
        $this->serviceManager = $this->getMockWithDisabledConstructor(\Zend\ServiceManager\ServiceManager::class);
    }

    public function test_getInstance_siteNominationAccepted_returnsTesterAtSiteNominationHandlerObject()
    {
        $this->runTestGetInstance_successful_for_action(
            PositionAtSiteNominationHandler::ACCEPTED,
            PositionAtSiteNominationHandler::class
        );
    }

    public function test_getInstance_siteNominationRejected_returnsTesterAtSiteNominationHandlerObject()
    {
        $this->runTestGetInstance_successful_for_action(
            PositionAtSiteNominationHandler::REJECTED,
            PositionAtSiteNominationHandler::class
        );
    }

    public function test_getInstance_organisationNominationAccepted_returnsTesterAtSiteNominationHandlerObject()
    {
        $this->runTestGetInstance_successful_for_action(
            PositionInOrganisationNominationHandler::ACCEPTED,
            PositionInOrganisationNominationHandler::class
        );
    }

    public function test_getInstance_organisationNominationRejected_returnsTesterAtSiteNominationHandlerObject()
    {
        $this->runTestGetInstance_successful_for_action(
            PositionInOrganisationNominationHandler::REJECTED,
            PositionInOrganisationNominationHandler::class
        );
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function test_getInstance_throwsNotFoundException()
    {
        AbstractNotificationActionHandler::getInstance('action not handled', $this->serviceManager);
    }

    private function runTestGetInstance_successful_for_action($action, $classPath)
    {
        $this->mockServiceManager(
            [
                EventService::class,
                EntityManager::class,
                NotificationService::class,
                UserFacadeLocal::class
            ]
        );

        $object = AbstractNotificationActionHandler::getInstance($action, $this->serviceManager);

        $this->assertInstanceOf($classPath, $object);
    }

    private function mockServiceManager($services)
    {
        $mockHandler = new MockHandler($this->serviceManager, $this);
        foreach ($services as $service) {
            $mockHandler
                ->next('get')
                ->with($service)
                ->will(
                    $this->returnValue(
                        $this->getMockWithDisabledConstructor($service)
                    )
                );
        }
    }
}
