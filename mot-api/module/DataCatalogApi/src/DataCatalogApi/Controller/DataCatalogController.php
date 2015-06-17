<?php
namespace DataCatalogApi\Controller;

use DataCatalogApi\Service\DataCatalogService;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;

use Zend\View\Model\JsonModel;

/**
 * Class DataCatalogController
 *
 * @package DataCatalogApi\Controller
 */
class DataCatalogController extends AbstractDvsaRestfulController
{
    public function getList()
    {
        /** @var \DataCatalogApi\Service\DataCatalogService $service */
        $service = $this->getServiceLocator()->get(DataCatalogService::class);

        return ApiResponse::jsonOk(
            [
                'decisions'             => $service->getEnforcementDecisionData(),
                'categories'            => $service->getEnforcementDecisionCategoryData(),
                'outcomes'              => $service->getEnforcementDecisionOutcomeData(),
                'scores'                => $service->getEnforcementDecisionScoreData(),
                'reinspections'         => $service->getEnforcementDecisionReinspectionOutcomeData(),
                'visitOutcomes'         => $service->getSiteAssessmentVisitOutcomeData(),
                'reasonsForSiteVisit'   => $service->getReasonForSiteVisitData(),
                'reasonsForCancel'      => $service->getMotTestReasonsForCancel(),
                'colours'               => $service->getColours(),
                'fuelTypes'             => $service->getFuelTypes(),
                'testerStatus'          => [],
                'demoTestResult'        => $service->getDemoTestResultData(),
                'reasonsForRefusal'     => $service->getReasonsForRefusal(),
                'vehicleClass'          => $service->getVehicleClasses(),
                'countryOfRegistration' => $service->getCountriesOfRegistration(),
                'transmissionType'      => $service->getTransmissionTypes(),
                'motTestType'           => $service->getMotTestTypes(),
                'organisationBusinessRole' => $service->getOrganisationBusinessRoles(),
                'siteBusinessRole'      => $service->getSiteBusinessRoles(),
                'brakeTestType'         => $service->getBrakeTestType(),
                'equipmentModelStatus'  => $service->getEquipmentModelStatus(),
                'reasonsForEmptyVRM'    => $service->getReasonsForEmptyVRM(),
                'reasonsForEmptyVIN'    => $service->getReasonsForEmptyVIN(),
            ]
        );
    }
}
