<?php

namespace VehicleApi\Controller;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaMotApi\Service\CertificateExpiryService;

/**
 * Class VehicleCertificateExpiryController.
 */
class VehicleCertificateExpiryController extends AbstractDvsaRestfulController
{
    const FIELD_CONTINGENCY_DATE = 'contingencyDate';

    /**
     * @param mixed $id
     * @return \Zend\View\Model\JsonModel
     */
    public function get($vehicleId)
    {
        $isDvla = filter_var($this->params()->fromRoute('isDvla', false), FILTER_VALIDATE_BOOLEAN);

        /** @var CertificateExpiryService $certificateExpiryService */
        $certificateExpiryService = $this->getServiceLocator()->get('CertificateExpiryService');
        $contingencyDate = ArrayUtils::tryGet($this->getRequest()->getQuery()->toArray(), self::FIELD_CONTINGENCY_DATE);

        if ($contingencyDate !== null) {
            $contingencyDate = DateUtils::toDateTime($contingencyDate);
        }
        $checkResult = $certificateExpiryService->getExpiryDetailsForVehicle($vehicleId, $isDvla, $contingencyDate);

        return ApiResponse::jsonOk(['checkResult' => $checkResult]);
    }
}
