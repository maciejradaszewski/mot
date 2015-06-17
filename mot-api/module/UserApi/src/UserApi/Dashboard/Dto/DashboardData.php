<?php

namespace UserApi\Dashboard\Dto;

use DvsaEntities\Entity\Notification;
use NotificationApi\Mapper\NotificationMapper;
use UserApi\Dashboard\BusinessLogic\RoleAndPermissionDetector;
use UserFacade\Role;

/**
 * All data for dashboard
 */
class DashboardData
{

    /** @var $displayRole string */
    private $hero;

    /** @var $displayRole AuthorisationForAuthorisedExaminer[] */
    private $authorisedExaminers;

    /** @var $specialNotice array */
    private $specialNotice;

    /** @var $notifications Notification[] */
    private $notifications;

    /** @var  $inProgressTestNumber integer */
    private $inProgressTestNumber;

    /** @var $roleAndPermissionsDetector RoleAndPermissionDetector */
    private $roleAndPermissionsDetector;

    /**
     * @param Role[]                               $roles
     * @param AuthorisationForAuthorisedExaminer[] $authorisedExaminers
     * @param array                                $specialNotice
     * @param Notification[]                       $notifications
     * @param array                                $vtcAuthorisations
     * @param integer                              $inProgressTestNumber
     * @param boolean                              $isTesterQualified
     * @param boolean                              $isTesterActive
     */
    public function __construct(
        $roles,
        $authorisedExaminers,
        $specialNotice,
        $notifications,
        $vtcAuthorisations,
        $inProgressTestNumber,
        $isTesterQualified,
        $isTesterActive
    ) {
        $this->setAuthorisedExaminers($authorisedExaminers);
        $this->setSpecialNotice(new SpecialNotice($specialNotice));
        $this->setNotifications($notifications);
        $this->setInProgressTestNumber($inProgressTestNumber);

        $this->roleAndPermissionsDetector = new RoleAndPermissionDetector(
            $roles,
            $vtcAuthorisations,
            $isTesterQualified,
            $isTesterActive
        );
        $this->setHero($this->roleAndPermissionsDetector->getHero());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $authorisedExaminers = [];
        /** @var $ae AuthorisationForAuthorisedExaminer */
        foreach ($this->getAuthorisedExaminers() as $ae) {
            $authorisedExaminers[] = $ae->toArray();
        }

        $notificationMapper = new NotificationMapper();
        $notificationExtractedList = [];
        /** @var $notification \DvsaEntities\Entity\Notification */
        foreach ($this->getNotifications() as $notification) {
            $notificationExtractedList[] = $notificationMapper->toArray($notification);
        }

        return [
            'hero'                 => $this->getHero(),
            'authorisedExaminers'  => $authorisedExaminers,
            'permissions'          => $this->roleAndPermissionsDetector->getPermissions(),
            'specialNotice'        => $this->getSpecialNotice()->toArray(),
            'notifications'        => $notificationExtractedList,
            'inProgressTestNumber' => $this->inProgressTestNumber
        ];
    }

    /**
     * @param AuthorisationForAuthorisedExaminer[] $authorisedExaminers
     *
     * @return DashboardData
     */
    public function setAuthorisedExaminers($authorisedExaminers)
    {
        $this->authorisedExaminers = $authorisedExaminers;

        return $this;
    }

    /**
     * @return AuthorisationForAuthorisedExaminer[]
     */
    public function getAuthorisedExaminers()
    {
        return $this->authorisedExaminers;
    }

    /**
     * @param string $displayRole
     *
     * @return DashboardData
     */
    public function setHero($displayRole)
    {
        $this->hero = $displayRole;

        return $this;
    }

    /**
     * @return string
     */
    public function getHero()
    {
        return $this->hero;
    }

    /**
     * @param SpecialNotice $specialNotice
     *
     * @return DashboardData
     */
    public function setSpecialNotice($specialNotice)
    {
        $this->specialNotice = $specialNotice;

        return $this;
    }

    /**
     * @return SpecialNotice
     */
    public function getSpecialNotice()
    {
        return $this->specialNotice;
    }

    /**
     * @param Notification[] $notifications
     *
     * @return DashboardData
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;

        return $this;
    }

    /**
     * @return Notification[]
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param integer $inProgressTestNumber
     *
     * @return DashboardData
     */
    public function setInProgressTestNumber($inProgressTestNumber)
    {
        $this->inProgressTestNumber = $inProgressTestNumber;

        return $this;
    }

    /**
     * @return integer
     */
    public function getInProgressTestNumber()
    {
        return $this->inProgressTestNumber;
    }
}
