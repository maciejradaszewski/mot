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
    const FIELD_VEHICLE_ID = 'id';
    const FIELD_SITE_ID = 'siteId';
    const FIELD_CONTINGENCY_DTO = 'contingencyDto';

    /** @var RetestEligibilityValidator */
    private $retestEligibilityValidator;

    public function __construct(RetestEligibilityValidator $retestEligibilityValidator)
    {
        $this->retestEligibilityValidator = $retestEligibilityValidator;
    }

    public function create($data)
    {
        $vehicleId = $this->params()->fromRoute(self::FIELD_VEHICLE_ID, null);
        $siteId = $this->params()->fromRoute(self::FIELD_SITE_ID, null);
        $contingencyDto = ArrayUtils::tryGet($data, self::FIELD_CONTINGENCY_DTO);

        if (!is_null($contingencyDto)) {
            $contingencyDto = DtoHydrator::jsonToDto($contingencyDto);
        }

        $result = [];

        try {
            $isEligible = $this->retestEligibilityValidator
                ->checkEligibilityForRetest($vehicleId, $siteId, $contingencyDto);
        } catch (BadRequestException $e) {
            $isEligible = false;
            foreach ($e->getErrors() as $reason) {
                $result['reasons'][] = $reason['displayMessage'];
            }
        }

        $result['isEligible'] = $isEligible;

        return ApiResponse::jsonOk($result);
    }
}
