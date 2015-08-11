<?php

namespace DvsaMotApi\Controller;

use DvsaCommon\Dto\MotTesting\ContingencyMotTestDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Service\Exception\BadRequestExceptionWithMultipleErrors;
use DvsaMotApi\Controller\Validator\EmergencyLogValidator;
use Zend\Session\Container;

/**
 * Class EmergencyLogController
 * @package DvsaMotApi\Controller
 */
class EmergencyLogController extends AbstractDvsaRestfulController
{
    const ERR_NOT_A_DTO = "Invalid / missing DTO in request";
    const ERR_DTO_INCOMPLETE = "DTO is not fully populated";

    const SESSION_NAME = 'EmergencyLogInfo';

    /** @var  EmergencyLogValidator */
    protected $validator;


    /**
     * Create a new emergency test. This involves hydrating the DTO which MUST
     * be an instance of ContingencyTestDto.
     *
     * @param $data Array presumably from a POST request
     * @return mixed
     *
     * @throws BadRequestException unless a ContingencyMotTestDto is hydrated
     */
    public function create($data)
    {
        $this->validator = new EmergencyLogValidator($this->getServiceLocator());

        /** @var ContingencyMotTestDto $dto */
        $dto = DtoHydrator::jsonToDto($data);

        if ($dto instanceof ContingencyMotTestDto) {
            if ($this->validateDto($dto)) {

                // need to pass back the emergencylog and message details to link the mot test to the emergency table
                $responseData = [
                    'emergencyLogId' => $this->validator->getEmergencyLog()->getId()
                ];
                return ApiResponse::jsonOk($responseData);
            } else {
                throw new BadRequestExceptionWithMultipleErrors($this->validator->getErrorMsg());
            }
        } else {
            throw new BadRequestException(
                self::ERR_NOT_A_DTO,
                BadRequestException::ERROR_CODE_INVALID_DATA
            );
        }
    }

    /**
     * Here we use the EmergencyLogValidator to ensure the DTO contains valid data.
     * We have to map field names first to ensure success (or failure!).
     *
     * @param ContingencyMotTestDto $dto
     *
     * @return bool
     */
    protected function validateDto(ContingencyMotTestDto $dto)
    {
        $testDateTime = \DateTime::createFromFormat('Y-m-d', $dto->getPerformedAt());

        $this->validator->validate(
            [
                'contingency_code' => $dto->getContingencyCode(), // db: emergency_log
                'tested_by_whom'   => $dto->getTestedByWhom(),    // alpha
                'tester_code'      => $dto->getTesterCode(),      // other | current
                'test_date'        => $testDateTime,              // DateTime of test
                'test_date_year'   => $dto->getDateYear(),        // year of test
                'test_date_month'  => $dto->getDateMonth(),       // month of test
                'test_date_day'    => $dto->getDateDay(),         // day of test
                'site_id'          => $dto->getSiteId(),          // site database id
                'reason_code'      => $dto->getReasonCode(),      // db: emergency_reason
                'reason_text'      => $dto->getReasonText(),      // when reason is "other"
            ]
        );
        return $this->validator->isValid();
    }
}
