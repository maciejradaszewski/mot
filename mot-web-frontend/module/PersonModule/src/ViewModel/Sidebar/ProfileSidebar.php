<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModule\ViewModel\Sidebar;

use Core\ViewModel\Sidebar\GeneralSidebar;
use Core\ViewModel\Sidebar\GeneralSidebarItemInterface;
use Core\ViewModel\Sidebar\GeneralSidebarLink;
use Core\ViewModel\Sidebar\GeneralSidebarLinkList;
use Core\ViewModel\Sidebar\GeneralSidebarStatusBox;
use Core\ViewModel\Sidebar\GeneralSidebarStatusItem;
use Core\ViewModel\Sidebar\SidebarBadge;
use Dvsa\Mot\Frontend\PersonModule\Security\PersonProfileGuard;
use DvsaClient\Entity\TesterAuthorisation;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;

/**
 * Class ProfileSidebar.
 */
class ProfileSidebar extends GeneralSidebar
{
    /**
     * @var int
     */
    private $personId;

    /**
     * @var PersonProfileGuard
     */
    private $personProfileGuard;

    /**
     * @var TesterAuthorisation
     */
    private $testerAuthorisation;

    /**
     * @param int                 $personId
     * @param PersonProfileGuard  $personProfileGuard
     * @param TesterAuthorisation $testerAuthorisation
     */
    public function __construct($personId, PersonProfileGuard $personProfileGuard,
                                TesterAuthorisation $testerAuthorisation)
    {
        $this->personId = $personId;
        $this->personProfileGuard = $personProfileGuard;
        $this->testerAuthorisation = $testerAuthorisation;

        $this->setUpStatusBox();
        $this->setUpAccountSecurityBox();
        $this->setUpAccountManagementBox();
        $this->setUpRelatedLinksSection();
    }

    /**
     * @param string $id of sidebar item to check for
     *
     * @return bool
     */

    public function hasItem($id)
    {
        $getId = function (GeneralSidebarItemInterface $item) {
            return $item->getId();
        };

        return in_array($id, array_map($getId, $this->getSidebarItems()));
    }

    /**
     * @param $code
     *
     * @return SidebarBadge CSS class modifier for status badge
     */
    private function getQualificationStatusModifier($code)
    {
        switch ($code) {
            case AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED:
                return SidebarBadge::normal();
                break;
            case AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED:
                return SidebarBadge::warning();
                break;
            case AuthorisationForTestingMotStatusCode::QUALIFIED:
                return SidebarBadge::success();
                break;
            case AuthorisationForTestingMotStatusCode::SUSPENDED:
                return SidebarBadge::alert();
                break;
            default :
                return SidebarBadge::normal();
        }
    }

    private function setUpStatusBox()
    {
        $statusBox = new GeneralSidebarStatusBox();
        $statusBox->setId('qualification_status');

        if (true === $this->personProfileGuard->shouldDisplayGroupAStatus()) {
            $statusBox->addItem(new GeneralSidebarStatusItem(
                "group-a",
                "Group A",
                $this->testerAuthorisation->getGroupAStatus()->getName(),
                $this->getQualificationStatusModifier($this->testerAuthorisation->getGroupAStatus()->getCode()),
                "Class 1 and 2"));
        }

        if (true === $this->personProfileGuard->shouldDisplayGroupBStatus()) {
            $statusBox->addItem(new GeneralSidebarStatusItem(
                "group-b",
                "Group B",
                $this->testerAuthorisation->getGroupBStatus()->getName(),
                $this->getQualificationStatusModifier($this->testerAuthorisation->getGroupBStatus()->getCode()),
                "Class 3, 4, 5 and 7"));
        }

        $this->addItem($statusBox);
    }

    private function setUpAccountSecurityBox()
    {
        if (!$this->personProfileGuard->canViewAccountSecurity()) {
            return;
        }
        $accountSecurityBox = new GeneralSidebarLinkList('Account security');
        $accountSecurityBox->setId('account_security');
        $accountSecurityBox->addLink(new GeneralSidebarLink('change-password', 'Change your password',
            '/profile/change-password'));
        $accountSecurityBox->addLink(new GeneralSidebarLink('reset-pin', 'Reset your PIN',
            PersonUrlBuilderWeb::securityQuestions()));

        $this->addItem($accountSecurityBox);
    }

    private function setUpAccountManagementBox()
    {
        if (!$this->personProfileGuard->canViewAccountManagement()) {
            return;
        }

        $accountManagementBox = new GeneralSidebarLinkList('Account management');
        $accountManagementBox->setId('account_management');

        if (true === $this->personProfileGuard->canResetAccount()) {
            $accountManagementBox->addLink(new GeneralSidebarLink('reset-by-email',
                'Reset account by email',
                '/user-admin/user-profile/' . $this->personId . '/claim-reset',
                'related-button--warning'));
            $accountManagementBox->addLink(new GeneralSidebarLink('reset-by-post',
                'Reset account by post',
                '/user-admin/user-profile/' . $this->personId . '/password-reset', '', 'or '));
        }

        if (true === $this->personProfileGuard->canSendUserIdByPost()) {
            $accountManagementBox->addLink(new GeneralSidebarLink('id-by-post',
                'Send User ID by post',
                '/user-admin/user-profile/' . $this->personId . '/username-recover'));
        }

        if (true === $this->personProfileGuard->canSendPasswordResetByPost()) {
            $accountManagementBox->addLink(new GeneralSidebarLink('password-by-post',
                'Send password reset by post',
                '/user-admin/user-profile/' . $this->personId . '/claim-reset/post'));
        }

        $this->addItem($accountManagementBox);
    }

    private function setUpRelatedLinksSection()
    {
        $relatedBox = new GeneralSidebarLinkList('Related');
        $relatedBox->setId('related');

        if (true === $this->personProfileGuard->shouldDisplayTradeRoles()) {
            $relatedBox->addLink(new GeneralSidebarLink('roles-and-associations',
                'Roles and associations',
                '/profile/' . $this->personId . '/trade-roles'));
        }

        if (true === $this->personProfileGuard->canManageDvsaRoles()) {
            $relatedBox->addLink(new GeneralSidebarLink('manage-roles',
                'Manage roles',
                '/user-admin/user-profile/' . $this->personId . '/manage-internal-role'));
        }

        if (true === $this->personProfileGuard->canChangeTesterQualificationStatus()) {
            $relatedBox->addLink(new GeneralSidebarLink('change-group-a-qualification',
                'Change Group A qualification status',
                'user-admin/user-profile/' . $this->personId . '/change-qualification-status/A'));
            $relatedBox->addLink(new GeneralSidebarLink('change-group-b-qualification',
                'Change Group B qualification status',
                'user-admin/user-profile/' . $this->personId . '/change-qualification-status/B'));
        }

        if (true === $this->personProfileGuard->canViewEventHistory()) {
            $relatedBox->addLink(new GeneralSidebarLink('event-history',
                'Event history',
                '/event/list/person/' . $this->personId));
        }

        if (!empty($relatedBox->getLinks())) {
            $this->addItem($relatedBox);
        }
    }
}
