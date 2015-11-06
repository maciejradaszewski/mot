<?php

namespace Site\ViewModel\Sidebar;

use Core\ViewModel\Sidebar\GeneralSidebar;
use Core\ViewModel\Sidebar\GeneralSidebarLink;
use Core\ViewModel\Sidebar\GeneralSidebarLinkList;
use Core\ViewModel\Sidebar\GeneralSidebarStatusBox;
use Core\ViewModel\Sidebar\GeneralSidebarStatusItem;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;

class VtsOverviewSidebar extends GeneralSidebar
{
    public function __construct($siteId, $siteStatus, $canReadEvents, $canSeeRecentCertificates, $canViewTestLogs)
    {
        $this->addStatusBox($siteStatus);
        $this->resolveLinks($siteId, $canReadEvents, $canSeeRecentCertificates, $canViewTestLogs);
    }

    private function addStatusBox($siteStatus)
    {
        $statusBox = new GeneralSidebarStatusBox();
        $modifierClass = '';
        if (strtolower($siteStatus) == 'approved') {
            $modifierClass = 'success';
        }

        $statusItem = new GeneralSidebarStatusItem('Status', $siteStatus, $modifierClass);
        $statusBox->addItem($statusItem);

        $this->addItem($statusBox);
    }

    private function resolveLinks($siteId, $canReadEvents, $canSeeRecentCertificates, $canViewTestLogs)
    {
        $relatedLinks = new GeneralSidebarLinkList('Related');
        if ($canReadEvents) {
            $relatedLinks->addLink($this->createEventsHistoryLink($siteId));
        }

        if ($canViewTestLogs) {
            $relatedLinks->addLink($this->createTestLogsLink($siteId));
        }

        if ($canSeeRecentCertificates) {
            $relatedLinks->addLink($this->createRecentCertificatesLink($siteId));
        }

        if (!$relatedLinks->isEmpty()) {
            $this->addItem($relatedLinks);
        }
    }

    private function createEventsHistoryLink($siteId)
    {
        $eventsUrl = '/event/list/site/' . $siteId;
        return new GeneralSidebarLink('event-history', 'Events history', $eventsUrl);
    }

    private function createRecentCertificatesLink($siteId)
    {
        $motCertsUrl = '/vehicle-testing-station/' . $siteId . '/mot-test-certificates';
        return new GeneralSidebarLink('mot-test-recent-certificates-link', 'MOT test certificates', $motCertsUrl);
    }

    private function createTestLogsLink($siteId)
    {
        $viewTestLogsUrl = VehicleTestingStationUrlBuilderWeb::motTestLog($siteId)->toString();

        return new GeneralSidebarLink('test-logs', 'Test logs', $viewTestLogsUrl);
    }
}
