<?php

namespace Site\ViewModel\Sidebar;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use Core\ViewModel\Sidebar\GeneralSidebar;
use Core\ViewModel\Sidebar\GeneralSidebarLink;
use Core\ViewModel\Sidebar\GeneralSidebarLinkList;
use Core\ViewModel\Sidebar\GeneralSidebarStatusBox;
use Core\ViewModel\Sidebar\GeneralSidebarStatusItem;
use Core\ViewModel\Badge\Badge;
use DvsaCommon\Auth\Assertion\ViewVtsTestQualityAssertion;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Enum\SiteStatusCode;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use DvsaFeature\FeatureToggles;
use Site\Service\RiskAssessmentScoreRagClassifier;

class VtsOverviewSidebar extends GeneralSidebar
{
    private $authorisationService;

    private $vtsId;

    private $featureToggles;

    private $hasBeenAssessed;

    private $siteStatusCatalog;

    private $viewVtsTestQualityAssertion;

    public function __construct(
        MotFrontendAuthorisationServiceInterface $authorisationService,
        FeatureToggles $featureToggles,
        $siteStatusCatalog,
        $vtsId,
        $siteStatusCode,
        $hasBeenAssessed,
        RiskAssessmentScoreRagClassifier $ragClassifier,
        $activeMotTestCount,
        ViewVtsTestQualityAssertion $viewVtsTestQualityAssertion,
        $assessmentDate
    ) {
        $this->authorisationService = $authorisationService;
        $this->featureToggles = $featureToggles;
        $this->vtsId = $vtsId;
        $this->hasBeenAssessed = $hasBeenAssessed;
        $this->siteStatusCatalog = $siteStatusCatalog;
        $this->viewVtsTestQualityAssertion = $viewVtsTestQualityAssertion;

        $this->addStatusBox($siteStatusCode, $activeMotTestCount, $ragClassifier, $assessmentDate);
        $this->resolveLinks();
    }

    private function addStatusBox($siteStatus, $activeMotTestCount, RiskAssessmentScoreRagClassifier $ragClassifier, $assessmentDate)
    {
        $statusBox = new GeneralSidebarStatusBox();
        $badge = $this->badgeForVtsStatus($siteStatus);

        $statusItem = new GeneralSidebarStatusItem('', 'Status', $this->siteStatusCatalog[$siteStatus], $badge);
        $statusBox->addItem($statusItem);

        if ($this->isVtsRiskEnabled()) {
            $scoreItem = new VtsOverviewRagStatus($ragClassifier, $assessmentDate);

            $statusBox->addItem($scoreItem);
        }

        if ($this->canViewTestInProgress()) {
            $activeTestsBadge = $activeMotTestCount ? Badge::info() : Badge::normal();
            $testsItem = new GeneralSidebarStatusItem('', 'Active MOT tests', $activeMotTestCount, $activeTestsBadge);

            $statusBox->addItem($testsItem);
        }

        $this->addItem($statusBox);
    }

    private function isVtsRiskEnabled()
    {
        return $this->featureToggles->isEnabled(FeatureToggle::VTS_RISK_SCORE);
    }

    private function canViewTestInProgress()
    {
        return $this->authorisationService->isGrantedAtSite(PermissionAtSite::VIEW_TESTS_IN_PROGRESS_AT_VTS, $this->vtsId);
    }

    private function canViewTestLogs()
    {
        return $this->authorisationService->isGrantedAtSite(PermissionAtSite::VTS_TEST_LOGS, $this->vtsId);
    }

    private function canReadEvents()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::EVENT_READ);
    }

    private function canViewRiskAssessment()
    {
        return $this->authorisationService->isGrantedAtSite(
            PermissionAtSite::VTS_VIEW_SITE_RISK_ASSESSMENT,
            $this->vtsId
        );
    }

    private function canChangeRiskAssessment()
    {
        return $this->authorisationService->isGrantedAtSite(
            PermissionAtSite::VTS_UPDATE_SITE_RISK_ASSESSMENT,
            $this->vtsId
        );
    }

    private function canAssessSite()
    {
        return $this->canChangeRiskAssessment()
        || ($this->canViewRiskAssessment());
    }

    private function resolveLinks()
    {
        $relatedLinks = new GeneralSidebarLinkList('Related');
        if ($this->canReadEvents()) {
            $relatedLinks->addLink($this->createEventsHistoryLink());
        }

        if ($this->canViewTestLogs()) {
            $relatedLinks->addLink($this->createTestLogsLink());
        }

        if ($this->canAssessSite()) {
            $relatedLinks->addLink($this->createSiteAssessmentLink());
        }

        if ($this->canAccessTestQualityInformation()) {
            $relatedLinks->addLink($this->createTestQualityInformationLink());
        }

        if($this->canViewTestersAssessments()){
            $relatedLinks->addLink($this->createTestersAssessmentLink());
        }

        if (!$relatedLinks->isEmpty()) {
            $this->addItem($relatedLinks);
        }
    }

    private function createEventsHistoryLink()
    {
        $eventsUrl = '/event/list/site/'.$this->vtsId;

        return new GeneralSidebarLink('event-history', 'Events history', $eventsUrl);
    }

    private function createTestLogsLink()
    {
        $viewTestLogsUrl = VehicleTestingStationUrlBuilderWeb::motTestLog($this->vtsId)->toString();

        return new GeneralSidebarLink('test-logs', 'Test logs', $viewTestLogsUrl);
    }

    private function createSiteAssessmentLink()
    {
        if ($this->canChangeRiskAssessment() && $this->hasBeenAssessed) {
            $siteAssessmentUrl = VehicleTestingStationUrlBuilderWeb::viewSiteRiskAssessment($this->vtsId);
            $linkValue = 'Site assessment';
        } elseif ($this->canViewRiskAssessment()) {
            $siteAssessmentUrl = VehicleTestingStationUrlBuilderWeb::addSiteRiskAssessment($this->vtsId);
            $linkValue = 'Add site assessment';
        } else {
            throw new \Exception('Cannot build url for person without permission to use it.');
        }

        return new GeneralSidebarLink('site-assessment-action-link', $linkValue, $siteAssessmentUrl);
    }

    private function badgeForVtsStatus($status)
    {
        switch ($status) {
            case SiteStatusCode::APPLIED:
                return Badge::normal();
            case SiteStatusCode::APPROVED:
                return Badge::success();
            case SiteStatusCode::EXTINCT:
                return Badge::alert();
            case SiteStatusCode::LAPSED:
                return Badge::normal();
            case SiteStatusCode::REJECTED:
                return Badge::alert();
            case SiteStatusCode::RETRACTED:
                return Badge::normal();
            default:
                return Badge::normal();
        }
    }

    private function canAccessTestQualityInformation()
    {
        return $this->viewVtsTestQualityAssertion->isGranted($this->vtsId);
    }

    private function createTestQualityInformationLink()
    {
        $tqiLink = '/vehicle-testing-station/'.$this->vtsId.'/test-quality';

        return new GeneralSidebarLink('site-test-quality', 'Test quality information', $tqiLink);
    }

    private function canViewTestersAssessments()
    {
        return $this->authorisationService->isGrantedAtSite(
            PermissionAtSite::TESTERS_ANNUAL_ASSESSMENT_VIEW,
            $this->vtsId
        );
    }

    private function createTestersAssessmentLink()
    {
        $testersAssessmentLink = '/vehicle-testing-station/' . $this->vtsId . '/testers-annual-assessment';

        return new GeneralSidebarLink('testers-annual-assessment', 'Testers annual assessment', $testersAssessmentLink);
    }
}
