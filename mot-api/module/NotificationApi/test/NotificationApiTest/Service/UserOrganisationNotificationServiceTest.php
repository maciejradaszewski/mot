<?php

namespace NotificationApiTest\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaCommon\Enum\RoleCode;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApiTest\Stub\ApiIdentityProviderStub;
use DvsaCommonApiTest\Transaction\TestTransactionExecutor;
use DvsaCommonTest\TestUtils\ArgCapture;
use DvsaCommonTest\TestUtils\Auth\GrantAllAuthorisationServiceStub;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationBusinessRole;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Role;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteBusinessRole;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\SiteBusinessRoleMapRepository;
use NotificationApi\Dto\Notification;
use NotificationApi\Service\NotificationService;
use NotificationApi\Service\PositionRemovalNotificationService;
use NotificationApi\Service\UserOrganisationNotificationService;
use SiteApi\Service\SitePositionService;
use DvsaEventApi\Service\EventService;
use DvsaAuthentication\Identity;

/**
 * Class SitePositionServiceTest
 *
 * @package SiteApiTest\Service
 */
class UserOrganisationNotificationServiceTest extends \PHPUnit_Framework_TestCase
{
    const AEDM_PERSON_ID = 12345678;
    /**
     * @var UserOrganisationNotificationService
     */
    protected $userOrganisationNotificationService;

    /**
     * @var Site
     */
    protected $vts;
    /**
     * @var  AbstractMotAuthorisationService $authorisationService
     */
    private $authorisationService;
    private $notificationService;
    private $siteBusinessRoleMapRepository;
    private $eventService;
    private $entityManager;
    private $positionRemovalNotificationService;
    /** @var ApiIdentityProviderStub */
    private $identityProvider;
    private $myId = 25;
    private $myPositionId = 324;
    private $myAeId = 11;

    /** @var Person */
    private $me;

    public function setUp()
    {
        $this->authorisationService = new GrantAllAuthorisationServiceStub();
        $this->notificationService = XMock::of(NotificationService::class);
        $this->siteBusinessRoleMapRepository = XMock::of(SiteBusinessRoleMapRepository::class);
        $this->entityManager = XMock::of(EntityManager::class);
        $this->eventService = XMock::of(EventService::class);
        $this->positionRemovalNotificationService = XMock::of(PositionRemovalNotificationService::class);
        $this->identityProvider = new ApiIdentityProviderStub();
        $this->identityProvider->setIdentity(new Identity(new Person()));
        $this->userOrganisationNotificationService = new UserOrganisationNotificationService(
            $this->notificationService,
            $this->positionRemovalNotificationService
        );
    }

    /**
     * @dataProvider organisationPositionDataProvider
     * @param $removedRole
     * @param $notificationRecipentId
     */
    public function testNotify_givenValidOrganisationPosition_properNotificationSent(
        $removedRole, $notificationRecipentId
    )
    {
        $notificationPromise = $this->notificationSent();

        $this->userOrganisationNotificationService->notifyOrganisationAboutRoleRemoval($removedRole);
        $notification = $notificationPromise->get();
        $this->assertEquals($notificationRecipentId, $notification['recipient']);
        $this->assertEquals(Notification::TEMPLATE_USER_REMOVED_OWN_ROLE, $notification['template']);
    }

    /**
     * @dataProvider sitePositionDataProvider
     * @param $removedRole
     * @param $notificationRecipentId
     */
    public function testNotify_givenValidSitePosition_properNotificationSent($removedRole, $notificationRecipentId)
    {
        $notificationPromise = $this->notificationSent();

        $this->userOrganisationNotificationService->notifySiteAboutRoleRemoval($removedRole);
        $notification = $notificationPromise->get();
        $this->assertEquals($notificationRecipentId, $notification['recipient']);
        $this->assertEquals(Notification::TEMPLATE_USER_REMOVED_OWN_ROLE, $notification['template']);
    }

    private function notificationSent()
    {
        $capNotification = ArgCapture::create();
        $this->notificationService->expects($this->atLeastOnce())->method("add")
            ->with($capNotification());

        return $capNotification;
    }

    private function createMyOrganisationPosition($roleName, $notificationRecipientId = 0, $sitePositionCode = '')
    {
        $this->me = new Person();
        $this->me->setId($this->myId);
        $this->vts = new Site();
        $this->vts->setId(2);
        $role = new Role();
        $role->setCode($sitePositionCode);
        $siteBusinessRoleMap = (new OrganisationBusinessRoleMap());
        $siteBusinessRoleMap->setPerson((new Person())->setId($notificationRecipientId)->setFirstName($sitePositionCode));
        $siteBusinessRoleMap->setOrganisationBusinessRole((new OrganisationBusinessRole())->setRole($role));

        $authExaminer = XMock::of(AuthorisationForAuthorisedExaminer::class);
        $authExaminer->expects($this->any())
            ->method('getDesignatedManager')
            ->willReturn((new Person())->setId(self::AEDM_PERSON_ID));

        $org = (new Organisation())->setId($this->myAeId);
        $org->setAuthorisedExaminer($authExaminer);
        $org->addPosition($siteBusinessRoleMap);

        $organisationBusinessRole = new OrganisationBusinessRole();
        $organisationBusinessRole->setName($roleName);

        $role = new Role();
        $role->setCode(RoleCode::AUTHORISED_EXAMINER);

        $organisationBusinessRole->setRole($role);

        $status = new BusinessRoleStatus();
        $status->setCode(BusinessRoleStatusCode::ACTIVE);

        $map = new OrganisationBusinessRoleMap();
        $map->setId($this->myPositionId);
        $map->setPerson($this->me);
        $map->setOrganisation($org);
        $map->setOrganisationBusinessRole($organisationBusinessRole);
        $map->setBusinessRoleStatus($status);

        return $map;
    }

    private function createMySitePosition($roleCode, $notificationRecipientId, $sitePositionCode)
    {
        $this->me = new Person();
        $this->me->setId($this->myId);
        $this->vts = new Site();
        $this->vts->setId(2);
        $vts = $this->vts;
        $person = new Person();
        $person->setId($this->myId);

        $positions = new ArrayCollection();
        $role = new SiteBusinessRole();
        $role->setCode($sitePositionCode);

        $siteBusinessRoleMap = (new SiteBusinessRoleMap());
        $siteBusinessRoleMap->setPerson((new Person())->setId($notificationRecipientId)->setFirstName($sitePositionCode));
        $positions->add($siteBusinessRoleMap->setSiteBusinessRole($role));

        $authExaminer = XMock::of(AuthorisationForAuthorisedExaminer::class);
        $authExaminer->expects($this->any())
            ->method('getDesignatedManager')
            ->willReturn((new Person())->setId(self::AEDM_PERSON_ID));
        $org = (new Organisation())->setId($this->myAeId);
        $org->setAuthorisedExaminer($authExaminer);

        $vts->setOrganisation($org);
        $vts->setPositions($positions);

        $role = new SiteBusinessRole();
        $role->setCode($roleCode);
        $status = (new BusinessRoleStatus())->setCode(BusinessRoleStatusCode::ACTIVE);

        $map = new SiteBusinessRoleMap();
        $map->setId($this->myId);
        $map->setPerson($person);
        $map->setSite($vts);
        $map->setSiteBusinessRole($role);
        $map->setBusinessRoleStatus($status);

        return $map;
    }

    public function organisationPositionDataProvider()
    {
        $notificationRecipient = rand(1,10000);
        return [
            [
                $this->createMyOrganisationPosition(
                    OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DELEGATE,
                    $notificationRecipient,
                    RoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER
                ),
                $notificationRecipient,
            ],
            [
                $this->createMyOrganisationPosition(
                    OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER,
                    $notificationRecipient,
                    RoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER
                ),
                $notificationRecipient,
            ],
            [
                $this->createMyOrganisationPosition(
                    OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_PRINCIPAL,
                    $notificationRecipient,
                    RoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER
                ),
                $notificationRecipient,
            ],
        ];

    }

    public function sitePositionDataProvider()
    {
        $notificationRecipient = rand(1,10000);
        return [
            [
                $this->createMySitePosition(
                    SiteBusinessRoleCode::TESTER,
                    $notificationRecipient,
                    SiteBusinessRoleCode::SITE_MANAGER
                ),
                $notificationRecipient,
            ],
            [
                $this->createMySitePosition(
                    SiteBusinessRoleCode::SITE_ADMIN,
                    $notificationRecipient,
                    SiteBusinessRoleCode::SITE_MANAGER
                ),
                $notificationRecipient,
            ],
            [
                $this->createMySitePosition(
                    SiteBusinessRoleCode::SITE_MANAGER,
                    $notificationRecipient,
                    ''
                ),
                self::AEDM_PERSON_ID,
            ],
            [
                $this->createMySitePosition(
                    SiteBusinessRoleCode::TESTER,
                    $notificationRecipient,
                    ''
                ),
                self::AEDM_PERSON_ID,
            ],
            [
                $this->createMySitePosition(
                    SiteBusinessRoleCode::SITE_ADMIN,
                    $notificationRecipient,
                    ''
                ),
                self::AEDM_PERSON_ID,
            ],
        ];

    }
}
