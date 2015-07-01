<?php

namespace VehicleApi\Controller;

use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;

/**
 * Class VehicleRetestEligibilityController
 */
class VehicleRetestEligibilityController extends AbstractDvsaRestfulController
{
    const FIELD_CONTINGENCY_DTO = 'contingencyDto';

    public function create($data)
    {
        $vehicleId      = $this->params()->fromRoute('id', null);
        $siteId         = $this->params()->fromRoute('siteId', null);
        $motTestNumber  = $this->params()->fromRoute('motTestNumber', null);
        $contingencyDto = ArrayUtils::tryGet($data, self::FIELD_CONTINGENCY_DTO);

        if (!is_null($contingencyDto)) {
            $contingencyDto = DtoHydrator::jsonToDto($contingencyDto);
        }

        /** @var RetestEligibilityValidator $retestEligibilityValidator */
        $retestEligibilityValidator = $this->getServiceLocator()->get('RetestEligibilityValidator');

        // TODO: validation of fields

        $isEligible = $retestEligibilityValidator->checkEligibilityForRetest($vehicleId, $siteId, $contingencyDto, $motTestNumber);

        return ApiResponse::jsonOk(['isEligible' => $isEligible]);
    }
}
