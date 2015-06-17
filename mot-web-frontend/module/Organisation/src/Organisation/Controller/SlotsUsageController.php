<?php

namespace Organisation\Controller;

use Dvsa\Mot\Frontend\Traits\ReportControllerTrait;
use Dvsa\Mot\Frontend\Traits\SearchQueryParamTrait;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Constants\QueryParam;
use Core\Controller\AbstractAuthActionController;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Model\CustomDateSearch;
use Dvsa\Mot\Frontend\Validator;
use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;
use DvsaCommon\UrlBuilder\SiteUrlBuilder;
use Organisation\Form\OrgSlotUsageFilter;
use Organisation\Traits\OrganisationServicesTrait;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class SlotsUsageController
 *
 */
class SlotsUsageController extends AbstractAuthActionController
{
    use OrganisationServicesTrait;
    use SearchQueryParamTrait;
    use ReportControllerTrait;

    const ROUTE_SLOTS_USAGE = 'authorised-examiner/slots/usage';

    const ORGANISATION_TABLE_CONFIG_NAME = 'slot-usage';
    const SITE_TABLE_CONFIG_NAME = 'site-slot-usage';

    const ROUTE_PARAM_NAME_PAGE = 'page';

    public function indexAction()
    {
        $organisationId = $this->params()->fromRoute('id');

        $this->getAuthorizationService()->assertGrantedAtOrganisation(
            PermissionAtOrganisation::AE_SLOTS_USAGE_READ, $organisationId
        );

        $mapperFactory = $this->getMapperFactory();
        $organisation = $mapperFactory->Organisation->getAuthorisedExaminer($organisationId);

        $currentPageId = $this->params()->fromRoute(self::ROUTE_PARAM_NAME_PAGE, 1);

        $fixedParams = [];
        $additionalParams = [
            'searchText' => $this->params()->fromQuery(QueryParam::SEARCH_TEXT),
        ];

        //load the form
        $form = $this->getForm(new OrgSlotUsageFilter());
        $form->setData($this->getRequest()->getQuery());

        $viewVariables = $this->buildReport(
            $form,
            $this->getRequest(),
            $currentPageId,
            self::ORGANISATION_TABLE_CONFIG_NAME,
            OrganisationUrlBuilder::organisationById($organisationId)->usage()->toString(),
            $fixedParams,
            $additionalParams
        );

        $viewVariables = array_merge(
            $viewVariables,
            [
                'organisation' => $organisation,
                'periodUsage' => $this->getOrgPeriodUsageData($organisationId),
                'slotUsageForCurrentQuery' => $this->apiData['totalSlotUsage'],
                'searchText' => $form->getData()[QueryParam::SEARCH_TEXT],
                'returnTo' => 'Slot Usage'
            ]
        );
        $viewVariables['route'] = $this->getEvent()->getRouteMatch()->getMatchedRouteName();
        $viewVariables['routeParameters'] = [
            'id' => $this->getEvent()->getRouteMatch()->getParam('id'),
            'page' => $this->getEvent()->getRouteMatch()->getParam('page')
        ];
        // Render the report
        $report = $this->renderReport(
            $viewVariables,
            'organisation/slots-usage/index',
            self::ORGANISATION_TABLE_CONFIG_NAME
        );

        return $report;
    }

    public function siteAction()
    {
        $organisationId = $this->params()->fromRoute('id');

        $mapperFactory = $this->getMapperFactory();
        $organisation = $mapperFactory->Organisation->getAuthorisedExaminer($organisationId);

        $siteId = $this->params()->fromRoute('sid');

        $this->getAuthorizationService()->assertGrantedAtSite(PermissionAtSite::SITE_SLOTS_USAGE_READ, $siteId);

        $mapperFactory = $this->getMapperFactory();
        $site = $mapperFactory->VehicleTestingStation->getById($siteId);

        $currentPageId = $this->params()->fromRoute(self::ROUTE_PARAM_NAME_PAGE, 1);

        $fixedParams = [];
        $additionalParams = [];

        //load the form
        $form = $this->getForm(new CustomDateSearch());
        $form
            ->setData($this->getRequest()->getQuery())
            ->isValid();

        $viewVariables = $this->buildReport(
            $form,
            $this->getRequest(),
            $currentPageId,
            self::SITE_TABLE_CONFIG_NAME,
            SiteUrlBuilder::site($siteId)->usage()->toString(),
            $fixedParams,
            $additionalParams
        );

        $viewVariables = array_merge(
            $viewVariables,
            [
                'organisation' => $organisation,
                'site' => $site,
                'periodUsage' => $this->getSitePeriodUsageData($siteId),
                'slotUsageForCurrentQuery' => $this->apiData['totalResultCount'],
            ]
        );
        $viewVariables['route'] = $this->getEvent()->getRouteMatch()->getMatchedRouteName();
        $viewVariables['routeParameters'] = [
            'id' => $this->getEvent()->getRouteMatch()->getParam('id'),
            'page' => $this->getEvent()->getRouteMatch()->getParam('page')
        ];
        return new ViewModel($viewVariables);
    }

    /**
     * @param int $orgId
     * @return mixed
     */
    private function getOrgPeriodUsageData($orgId)
    {
        $periodUsage = $this->getPeriods();
        $baseUrl = OrganisationUrlBuilder::organisationById($orgId)->usage()->periodData()->toString();

        $apiUrl = $baseUrl . '?' . http_build_query(['period' => $periodUsage]);

        return $this->getRestClient()->get($apiUrl)['data'];
    }

    /**
     * @param int $siteId
     * @return mixed
     */
    private function getSitePeriodUsageData($siteId)
    {
        $periodUsage = $this->getPeriods();
        $baseUrl = SiteUrlBuilder::site($siteId)->usage()->periodData()->toString();

        $apiUrl = $baseUrl . '?' . http_build_query(['period' => $periodUsage]);

        return $this->getRestClient()->get($apiUrl)['data'];
    }

    /**
     * @return array
     */
    private function getPeriods()
    {
        $todayEnd = DateTimeApiFormat::datetime(DateUtils::nowAsUserDateTime()->setTime(23, 59, 59));
        $fromList = [0, 7, 30, 365];

        $period = [];
        foreach ($fromList as $from) {
            $period[] = [
                'from' => $this->calculatePeriodStart($from),
                'to' => $todayEnd,
            ];
        }

        return $period;
    }

    /**
     * @param int $days
     * @return string
     */
    private function calculatePeriodStart($days)
    {
        $date = DateUtils::nowAsUserDateTime();
        if ($days > 0) {
            $date = $date->sub(new \DateInterval('P' . $days . 'D'));
        }

        return DateTimeApiFormat::date($date);
    }
}
