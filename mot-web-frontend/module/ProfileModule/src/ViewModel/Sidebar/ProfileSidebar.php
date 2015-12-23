<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\ProfileModule\ViewModel\Sidebar;

use Core\ViewModel\Sidebar\GeneralSidebar;
use Core\ViewModel\Sidebar\GeneralSidebarLink;
use Core\ViewModel\Sidebar\GeneralSidebarLinkList;
use Core\ViewModel\Sidebar\GeneralSidebarStatusBox;
use Core\ViewModel\Sidebar\GeneralSidebarStatusItem;
use Core\ViewModel\Sidebar\SidebarBadge;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;

class ProfileSidebar extends GeneralSidebar
{
    /**
     * @var GeneralSidebarStatusBox
     */
    private $statusBox;

    /**
     * @var GeneralSidebarLinkList
     */
    private $accountSecurityBox;

    /**
     * @var GeneralSidebarLinkList
     */
    private $accountManagementBox;

    /**
     * @var GeneralSidebarLinkList
     */
    private $relatedBox;

    /**
     * @var \DvsaClient\Entity\TesterAuthorisation
     */
    private $authorisation;

    /**
     * @var bool
     */
    private $isViewingOwnProfile;

    /**
     * @var int
     */
    private $personId;

    /**
     * @var MotAuthorisationServiceInterface
     */
    private $authorisationService;

    /**
     * @param int                                    $personId
     * @param \DvsaClient\Entity\TesterAuthorisation $authorisation
     * @param bool                                   $isViewingOwnProfile
     * @param MotAuthorisationServiceInterface       $authorisationService
     * @param array                                  $tradeRolesAndAssociations
     */
    public function __construct($personId, $authorisation, $isViewingOwnProfile, $authorisationService, $tradeRolesAndAssociations)
    {
        $this->authorisation = $authorisation;
        $this->isViewingOwnProfile = $isViewingOwnProfile;
        $this->personId = $personId;
        $this->authorisationService = $authorisationService;

        $this->setUpStatusBox();

        $this->setUpAccountSecurityBox();

        $this->setUpAccountManagementBox();

        $this->setUpRelatedLinksSection();
    }

    /**
     * @param $code
     *
     * @return SidebarBadge CSS class modifier for status badge
     */
    private function getQualificationStatusModifier($code)
    {
        switch ($code) {
            case 'ITRN':
                return SidebarBadge::normal();
                break;
            case 'DMTN':
                return SidebarBadge::warning();
                break;
            case 'QLFD':
                return SidebarBadge::success();
                break;
            case 'SPND':
                return SidebarBadge::alert();
                break;
            default :
                return SidebarBadge::normal();
        }
    }

    private function setUpStatusBox()
    {
        $this->statusBox = new GeneralSidebarStatusBox();
        $this->statusBox->setId('qualification_status');

        if (null !== $this->authorisation->getGroupAStatus()->getCode() && 'ITRN' != $this->authorisation->getGroupAStatus()->getCode()) {
            $this->statusBox->addItem(new GeneralSidebarStatusItem(
                "group-a",
                "Group A",
                $this->authorisation->getGroupAStatus()->getName(),
                $this->getQualificationStatusModifier($this->authorisation->getGroupAStatus()->getCode()),
                "Class 1 and 2"));
        }

        if (null !== $this->authorisation->getGroupBStatus()->getCode() && 'ITRN' != $this->authorisation->getGroupBStatus()->getCode()) {
            $this->statusBox->addItem(new GeneralSidebarStatusItem(
                "group-b",
                "Group B",
                $this->authorisation->getGroupBStatus()->getName(),
                $this->getQualificationStatusModifier($this->authorisation->getGroupBStatus()->getCode()),
                "Class 3, 4, 5 and 7"));
        }
        $this->addItem($this->statusBox);
    }

    private function setUpAccountSecurityBox()
    {
        $this->accountSecurityBox = new GeneralSidebarLinkList('Account security');
        $this->accountSecurityBox->setId('account_security');
        $this->accountSecurityBox->addLink(new GeneralSidebarLink('change-password', 'Change your password',
            '/profile/change-password'));
        $this->accountSecurityBox->addLink(new GeneralSidebarLink('reset-pin', 'Reset your PIN',
            PersonUrlBuilderWeb::securityQuestions()));
        if ($this->isViewingOwnProfile) {
            $this->addItem($this->accountSecurityBox);
        }
    }

    private function setUpAccountManagementBox()
    {
        $this->accountManagementBox = new GeneralSidebarLinkList('Account management');
        $this->accountManagementBox->setId('account_management');
        if ($this->authorisationService->isGranted(PermissionInSystem::USER_ACCOUNT_RECLAIM)) {
            $this->accountManagementBox->addLink(new GeneralSidebarLink('reset-by-email',
                'Reset account by email',
                '/user-admin/user-profile/' . $this->personId . '/claim-reset',
                'related-button--warning'));
            $this->accountManagementBox->addLink(new GeneralSidebarLink('reset-by-post',
                'Reset account by post',
                '/user-admin/user-profile/' . $this->personId . '/password-reset', '', 'or '));
        }
        if ($this->authorisationService->isGranted(PermissionInSystem::USERNAME_RECOVERY)) {
            $this->accountManagementBox->addLink(new GeneralSidebarLink('id-by-post',
                'Send User ID by post',
                '/user-admin/user-profile/' . $this->personId . '/username-recover'));
        }
        if ($this->authorisationService->isGranted(PermissionInSystem::USER_PASSWORD_RESET)) {
            $this->accountManagementBox->addLink(new GeneralSidebarLink('password-by-post',
                'Send password reset by post',
                '/user-admin/user-profile/' . $this->personId . '/claim-reset/post'));
        }
        if (!empty($this->accountManagementBox->getLinks())) {
            $this->addItem($this->accountManagementBox);
        }
    }

    private function setUpRelatedLinksSection()
    {
        $this->relatedBox = new GeneralSidebarLinkList('Related');
        $this->relatedBox->setId('related');
        if ($this->authorisationService->isGranted(PermissionInSystem::VIEW_TRADE_ROLES_OF_ANY_USER) && !empty($tradeRolesAndAssociations)) {
            $this->relatedBox->addLink(new GeneralSidebarLink('roles-and-associations',
                'Roles and associations',
                '/profile/' . $this->personId . '/trade-roles'));
        }
        if ($this->authorisationService->isGranted(PermissionInSystem::MANAGE_DVSA_ROLES) && !$this->isViewingOwnProfile) {
            $this->relatedBox->addLink(new GeneralSidebarLink('manage-roles',
                'Manage roles',
                '/user-admin/user-profile/' . $this->personId . '/manage-internal-role'));
        }
        if ($this->authorisationService->isGranted(PermissionInSystem::ALTER_TESTER_AUTHORISATION_STATUS)) {
            $this->relatedBox->addLink(new GeneralSidebarLink('change-group-a-qualification',
                'Change Group A qualification status',
                'user-admin/user-profile/' . $this->personId . '/change-qualification-status/A'));
            $this->relatedBox->addLink(new GeneralSidebarLink('change-group-b-qualification',
                'Change Group B qualification status',
                'user-admin/user-profile/' . $this->personId . '/change-qualification-status/B'));
        }
        if ($this->authorisationService->isGranted(PermissionInSystem::LIST_EVENT_HISTORY)) {
            $this->relatedBox->addLink(new GeneralSidebarLink('event-history',
                'Event history',
                '/event/list/person/' . $this->personId));
        }
        if (!empty($this->relatedBox->getLinks())) {
            $this->addItem($this->relatedBox);
        }
    }

    /**
     * @return GeneralSidebarStatusBox
     */
    public function getStatusBox()
    {
        return $this->statusBox;
    }

    /**
     * @return GeneralSidebarLinkList
     */
    public function getAccountSecurityBox()
    {
        return $this->accountSecurityBox;
    }

    /**
     * @return GeneralSidebarLinkList
     */
    public function getAccountManagementBox()
    {
        return $this->accountManagementBox;
    }

    /**
     * @return GeneralSidebarLinkList
     */
    public function getRelatedBox()
    {
        return $this->relatedBox;
    }

    /**
     * @param string $id of sidebar item to check for
     *
     * @return bool
     */

    public function hasItem($id)
    {
        $getId = function ($item) {
            return $item->getId();
        };

        return in_array($id, array_map($getId, $this->getSidebarItems()));
    }
}
