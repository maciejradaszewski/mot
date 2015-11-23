<?php

namespace VehicleApi\Controller;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaMotApi\Service\CertificateExpiryService;

/**
 * Class VehicleCertificateExpiryController.
 */
class VehicleCertificateExpiryController extends AbstractDvsaRestfulController
{
    const FIELD_CONTINGENCY_DATETIME = 'contingencyDatetime';

    /**
     * @param mixed $vehicleId
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function get($vehicleId)
    {
        $isDvla = filter_var($this->params()->fromRoute('isDvla', false), FILTER_VALIDATE_BOOLEAN);

        /** @var CertificateExpiryService $certificateExpiryService */
        $certificateExpiryService = $this->getServiceLocator()->get('CertificateExpiryService');
        $contingencyDatetime = ArrayUtils::tryGet($this->getRequest()->getQuery()->toArray(), self::FIELD_CONTINGENCY_DATETIME);

        if ($contingencyDatetime !== null) {
            $contingencyDatetime = DateUtils::toDateTime($contingencyDatetime);
        }
        $checkResult = $certificateExpiryService->getExpiryDetailsForVehicle($vehicleId, $isDvla, $contingencyDatetime);

        return ApiResponse::jsonOk(['checkResult' => $checkResult]);
    }
}
