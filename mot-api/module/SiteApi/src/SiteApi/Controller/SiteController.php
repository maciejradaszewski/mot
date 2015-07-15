<?php
namespace SiteApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use SiteApi\Service\SiteService;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;

/**
 * Controller which creates/edits new VTS
 */
class SiteController extends AbstractDvsaRestfulController
{
    const SITE_NUMBER_QUERY_PARAMETER = 'siteNumber';

    const SITE_ID_REQUIRED_MESSAGE = 'Query parameter site Id is required';
    const SITE_ID_REQUIRED_DISPLAY_MESSAGE = 'You need to enter a Site Id to perform the search';
    const SITE_NUMBER_REQUIRED_MESSAGE = 'Query parameter siteNumber is required';
    const SITE_NUMBER_REQUIRED_DISPLAY_MESSAGE = 'You need to enter a Site Number to perform the search';
    const SITE_NUMBER_INVALID_DATA_MESSAGE = 'siteNumber: non alphanumeric characters found';
    const SITE_NUMBER_INVALID_DATA_DISPLAY_MESSAGE = 'Site number should contain alphanumeric characters only';

    /**
     * @var SiteService
     */
    private $service;

    /**
     * @param SiteService $service
     */
    public function __construct(SiteService $service)
    {
        $this->service = $service;
    }

    public function create($data)
    {
        $result = $this->service->create($data);

        return ApiResponse::jsonOk($result);
    }

    /**
     * @return JsonModel
     * @deprecated VM-7285 (An update site data functionality was removed and changed to update
     * contact details of VTS only. An update contact details use SiteContactController, so this
     * method not used anywhere, but I left it, because it wil be need in future)
     */
    public function update($id, $data)
    {
        $result = $this->service->update($id, $data);

        return ApiResponse::jsonOk($result);
    }

    public function get($id)
    {
        if ($id === null) {
            return $this->getBadRequestResponseModelForId();
        }

        $isNeedDto = (boolean)$this->params()->fromQuery('dto');
        $data = $this->service->getVehicleTestingStationData($id, $isNeedDto);

        if ($isNeedDto) {
            return ApiResponse::jsonOk($data);
        }

        return ApiResponse::jsonOk(["vehicleTestingStation" => $data]);
    }

    public function siteByIdAction()
    {
        $id = $this->params()->fromRoute('id');
        if ($id === null) {
            return $this->getBadRequestResponseModelForId();
        }

        $siteData = $this->service->getSiteData($id);

        return ApiResponse::jsonOk(["vehicleTestingStation" => $siteData]);
    }

    /**
     * Find one VTS by siteNumber
     *
     * @return JsonModel
     */
    public function findBySiteNumberAction()
    {
        $siteNumber = $this->params()->fromRoute('sitenumber');
        if ($siteNumber === null) {
            return $this->getBadRequestResponseModelForNumber();
        }

        $isNeedDto = (boolean)$this->params()->fromQuery('dto');

        $data = $this->service->getVehicleTestingStationDataBySiteNumber($siteNumber);

        if ($isNeedDto) {
            return ApiResponse::jsonOk($data);
        }

        return ApiResponse::jsonOk(["vehicleTestingStation" => $data]);
    }

    private function getBadRequestResponseModelForId()
    {
        return $this->returnBadRequestResponseModel(
            self::SITE_ID_REQUIRED_MESSAGE,
            self::ERROR_CODE_REQUIRED,
            self::SITE_ID_REQUIRED_DISPLAY_MESSAGE
        );
    }

    private function getBadRequestResponseModelForNumber()
    {
        return $this->returnBadRequestResponseModel(
            self::SITE_NUMBER_REQUIRED_MESSAGE,
            self::ERROR_CODE_REQUIRED,
            self::SITE_NUMBER_REQUIRED_DISPLAY_MESSAGE
        );
    }
}
