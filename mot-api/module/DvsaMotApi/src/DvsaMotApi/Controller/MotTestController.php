<?php
namespace DvsaMotApi\Controller;

use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommon\Constants\Network;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaDocument\Service\Document\DocumentService;
use DvsaEntities\Entity\MotTest;
use DvsaMotApi\Controller\Validator\CreateMotTestRequestValidator;
use DvsaMotApi\Service\CertificateCreationService;
use DvsaMotApi\Service\MotTestService;
use SiteApi\Service\SiteService;
use Zend\View\Model\JsonModel;

/**
 * Class MotTestController
 *
 * @package DvsaMotApi\Controller
 */
class MotTestController extends AbstractDvsaRestfulController implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    const FIELD_VEHICLE_ID = 'vehicleId';
    const FIELD_DVLA_VEHICLE_ID = 'dvlaVehicleId';
    const FIELD_VTS_ID = 'vehicleTestingStationId';
    const FIELD_HAS_REGISTRATION = 'hasRegistration';
    const FIELD_MOT_TEST_TYPE = 'motTestType';
    const FIELD_MOT_TEST_NUMBER_ORIGINAL = 'motTestNumberOriginal';
    const FIELD_ODOMETER_READING = 'odometerReading';
    const FIELD_MOT_TEST_COMPLAINT_REF = 'complaintRef';
    const FIELD_COLOURS = 'colours';
    const FIELD_COLOURS_PRIMARY = 'primaryColour';
    const FIELD_COLOURS_SECONDARY = 'secondaryColour';
    const FIELD_FUEL_TYPE_ID = 'fuelTypeId';
    const FIELD_VEHICLE_CLASS_CODE = "vehicleClassCode";
    const FIELD_REASON_DIFFERENT_TESTER_CODE = 'differentTesterReasonCode';
    const FIELD_SITEID = 'siteid';
    const FIELD_LOCATION = 'location';
    const FIELD_FLAG_PRIVATE = 'flagPrivate';
    const FIELD_ONE_PERSON_TEST = 'onePersonTest';
    const FIELD_ONE_PERSON_RE_INSPECTION = 'onePersonReInspection';
    const FIELD_ONE_TIME_PASSWORD = 'oneTimePassword';
    const FIELD_CONTINGENCY = 'contingencyId';
    const FIELD_CONTINGENCY_DTO = 'contingencyDto';
    const FIELD_CLIENT_IP = 'clientIp';

    const SITE_NUMBER_QUERY_PARAMETER = 'siteNumber';
    const VEHICLE_ID_QUERY_PARAMETER = 'vehicleId';
    const SITE_NUMBER_REQUIRED_MESSAGE = 'Query parameter siteNumber is required';
    const SITE_NUMBER_REQUIRED_DISPLAY_MESSAGE = 'Missing site number. Please enter a site number to search for';
    const ERROR_MSG_FAILED_TO_UPDATE_TEST = 'Failed to update the location for the site';
    const ERROR_MSG_EITHER_OR_LOCATION = 'Please supply a site ID or a location, not both';
    const ERROR_MSG_SITE_NUMBER_INVALID = 'Site number invalid';
    const ERROR_UNABLE_TO_PERFORM_SEARCH_WITH_PARAMS = 'Unable to perform search with given parameters';

    public function __construct()
    {
        $this->setIdentifierName('motTestNumber');
    }

    public function get($motTestNumber)
    {
        $motTestData = $this->getMotTestService()->getMotTestData($motTestNumber);

        return ApiResponse::jsonOk($motTestData);
    }

    /**
     * Returns minimal MOT data
     *
     * Currently used for Brake test journey which does not
     * require the fully hydrated MOT object
     *
     * @return JsonModel
     * @throws \DvsaCommonApi\Service\Exception\ForbiddenException
     */
    public function getMinimalMotAction()
    {
        $motTestNumber    = $this->params()->fromRoute('motTestNumber');
        $motTestData = $this->getMotTestService()->getMotTestData($motTestNumber, true);

        return ApiResponse::jsonOk($motTestData);
    }

    public function findMotTestNumberAction()
    {
        $request = $this->getRequest();

        $motTestId = $request->getQuery('motTestId');
        $motTestNumber = $request->getQuery('motTestNumber');
        $v5c = $request->getQuery('v5c');

        if ($v5c && $motTestNumber) {
            return ApiResponse::jsonError(self::ERROR_UNABLE_TO_PERFORM_SEARCH_WITH_PARAMS);
        }

        $service = $this->getMotTestService();

        if ($motTestId) {
            if ($v5c) {
                return ApiResponse::jsonOk(
                    $service->findMotTestNumberByMotTestIdAndV5c($motTestId, $v5c)
                );
            } elseif ($motTestNumber) {
                return ApiResponse::jsonOk(
                    $service->findMotTestNumberByMotTestIdAndMotTestNumber($motTestId, $motTestNumber)
                );
            }
        }

        return ApiResponse::jsonError(self::ERROR_UNABLE_TO_PERFORM_SEARCH_WITH_PARAMS);
    }

    public function create($data)
    {
        CreateMotTestRequestValidator::validate($data);

        $person = $this->getIdentity()->getPerson();

        $dvlaVehicleId = array_key_exists(self::FIELD_DVLA_VEHICLE_ID, $data)
            ? (string)$data[self::FIELD_DVLA_VEHICLE_ID] : null;
        $vehicleId = array_key_exists(self::FIELD_VEHICLE_ID, $data)
            ? (string)$data[self::FIELD_VEHICLE_ID] : null;

        // Unless a new siteid has been specified (for a reinspection) we want to maintain the old value...
        $vehicleTestingStationId = (int)$data[self::FIELD_VTS_ID];
        $primaryColour = $data[self::FIELD_COLOURS_PRIMARY];
        $secondaryColour = ArrayUtils::tryGet($data, self::FIELD_COLOURS_SECONDARY);
        $fuelTypeCode = $data[self::FIELD_FUEL_TYPE_ID];
        $vehicleClassCode = ArrayUtils::tryGet($data, self::FIELD_VEHICLE_CLASS_CODE);
        $hasRegistration = ArrayUtils::tryGet($data, self::FIELD_HAS_REGISTRATION, false);
        $motTestNumberOriginal = ArrayUtils::tryGet($data, self::FIELD_MOT_TEST_NUMBER_ORIGINAL);
        $complaintRef = ArrayUtils::tryGet($data, self::FIELD_MOT_TEST_COMPLAINT_REF);
        $motTestTypeCode = ArrayUtils::tryGet($data, self::FIELD_MOT_TEST_TYPE, MotTestTypeCode::NORMAL_TEST);
        $flagPrivate = ArrayUtils::tryGet($data, self::FIELD_FLAG_PRIVATE, false);
        $oneTimePassword = ArrayUtils::tryGet($data, self::FIELD_ONE_TIME_PASSWORD);
        $contingencyId = ArrayUtils::tryGet($data, self::FIELD_CONTINGENCY);
        $contingencyDto = ArrayUtils::tryGet($data, self::FIELD_CONTINGENCY_DTO);

        if (!is_null($contingencyDto)) {
            $contingencyDto = DtoHydrator::jsonToDto($contingencyDto);
        }

        /** @var MotTest $motTest */
        $motTest = $this->getMotTestService()->createMotTest(
            $person,
            $vehicleId,
            $vehicleTestingStationId,
            $primaryColour,
            $secondaryColour,
            $fuelTypeCode,
            $vehicleClassCode,
            $hasRegistration,
            $dvlaVehicleId,
            $motTestTypeCode,
            $motTestNumberOriginal,
            $complaintRef,
            $flagPrivate,
            $oneTimePassword,
            $contingencyId,
            $contingencyDto
        );

        return ApiResponse::jsonOk(["motTestNumber" => $motTest->getNumber()]);
    }

    /**
     * Perform an UPDATE of an existing MOT record by checking for:
     *
     * siteid/location: mutually exclusive
     *
     * @param $motTestNumber int contains the re-inspection database row id
     * @param $data  Array the PUT data from the re-inspection summary form
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function update($motTestNumber, $data)
    {
        $locationSiteText = null;
        $locationSiteId = null;
        $onePersonTest = null;
        $onePersonReInspection = null;
        $errors = [];

        $motTest = $this->getMotTestService()->getMotTest($motTestNumber);

        if ($motTest && ($motTest->getMotTestType()->getIsReinspection())) {
            if (isset($data['operation']) && ($data['operation'] == 'updateOnePersonTest')) {
                list($onePersonTest, $onePersonReInspection)
                    = $this->validateOnePersonTest($motTestNumber, $data, $errors);

                if (count($errors) > 0) {
                    return $this->returnBadRequestResponseModelWithErrors($errors);
                }

                if ($onePersonTest && $onePersonReInspection) {
                    $onePersonTestUpdated = $this->getMotTestService()
                        ->updateOnePersonTest($motTestNumber, $onePersonTest, $onePersonReInspection);

                    if ($onePersonTestUpdated) {
                        return ApiResponse::jsonOk(['updated' => true]);
                    }
                }
            }

            if (isset($data['operation']) && ($data['operation'] == 'updateSiteLocation')) {
                list($locationSiteId, $locationSiteText)
                    = $this->performOffsiteValidation($motTestNumber, $data, $errors);

                if (count($errors) > 0) {
                    return $this->returnBadRequestResponseModelWithErrors($errors);
                }

                $locationUpdated = $this->getMotTestService()->updateMotTestLocation(
                    $this->getUsername(),
                    $motTestNumber,
                    $locationSiteId,
                    $locationSiteText
                );

                if (!$locationUpdated) {
                    return $this->returnBadRequestResponseModelWithErrors(
                        [
                            $this->makeErrorMessage(
                                self::ERROR_MSG_FAILED_TO_UPDATE_TEST,
                                self::ERROR_CODE_REQUIRED,
                                self::ERROR_MSG_FAILED_TO_UPDATE_TEST
                            )
                        ]
                    );
                }
                return ApiResponse::jsonOk(['updated' => true]);
            }
        }

        return null;
    }

    /**
     * If a reinspection has been specified then we need to ensure that a location has been
     * specified.
     *
     * @param $motId  String the MOT id from the URL parameter
     * @param $data   Array  the POST data sent from the page
     * @param $errors Array& accumulator for errors
     *
     * @return array [new site id, new site location text]
     * @see http://objitsu.blogspot.co.uk/2013/01/php-and-lisp-multiple-value-bind-mvb.html
     */
    protected function performOffsiteValidation($motId, $data, &$errors)
    {
        $response = [null, null];
        $motTest = $this->getMotTestService()->getMotTest($motId);

        if ($motTest && ($motTest->getMotTestType()->getIsReinspection())) {
            $siteIdExists = array_key_exists(self::FIELD_SITEID, $data) && !empty($data[self::FIELD_SITEID]);
            $locationExists = array_key_exists(self::FIELD_LOCATION, $data) && !empty($data[self::FIELD_LOCATION]);

            if (!$siteIdExists && !$locationExists) {
                $errors[] = $this->makeFieldIsRequiredError(self::FIELD_SITEID);
                return $response;
            }

            if ($siteIdExists && $locationExists) {
                $errors[] = $this->makeErrorMessage(self::ERROR_MSG_EITHER_OR_LOCATION);
                return $response;
            }

            if ($siteIdExists) {
                if (1 === preg_match('/\w+/', $data[self::FIELD_SITEID], $match)) {
                    $submittedId = $match[0];
                    try {
                        $siteData = $this->getVehicleTestingStationService()
                            ->getVehicleTestingStationDataBySiteNumber($submittedId);

                        $response = [$siteData['id'], null];
                    } catch (\Exception $e) {
                        $errors[] = $this->makeErrorMessage(
                            self::ERROR_MSG_SITE_NUMBER_INVALID,
                            self::ERROR_CODE_REQUIRED,
                            self::ERROR_MSG_SITE_NUMBER_INVALID
                        );
                    }
                } else {
                    $errors[] = $this->makeErrorMessage(
                        self::ERROR_MSG_SITE_NUMBER_INVALID,
                        self::ERROR_CODE_REQUIRED,
                        self::ERROR_MSG_SITE_NUMBER_INVALID
                    );
                }
            } else {
                $response = [null, trim($data[self::FIELD_LOCATION])];
            }
        }
        return $response;
    }

    /**
     * Validate One Person Test Fields
     *
     * @param string $motId the MOT id from the URL parameter
     * @param array  $data the POST data sent from the page
     * @param array  $errors accumulator for errors
     *
     * @return array response
     */
    protected function validateOnePersonTest($motId, $data, &$errors)
    {
        $response = [null, null];
        $motTest = $this->getMotTestService()->getMotTest($motId);
        if ($motTest && ($motTest->getMotTestType()->getIsReinspection())) {
            $onePersonTest = array_key_exists(self::FIELD_ONE_PERSON_TEST, $data)
                && !empty($data[self::FIELD_ONE_PERSON_TEST]);

            $onePersonReInspection = array_key_exists(self::FIELD_ONE_PERSON_RE_INSPECTION, $data)
                && !empty($data[self::FIELD_ONE_PERSON_RE_INSPECTION]);

            if (!$onePersonTest) {
                $errors[] = $this->makeFieldIsRequiredError(self::FIELD_ONE_PERSON_TEST);
                return $response;
            }

            if (!$onePersonReInspection) {
                $errors[] = $this->makeFieldIsRequiredError(self::FIELD_ONE_PERSON_RE_INSPECTION);
                return $response;
            }

            return [$data[self::FIELD_ONE_PERSON_TEST], $data[self::FIELD_ONE_PERSON_RE_INSPECTION]];
        }
        return $response;
    }

    /**
     * Answers the actual string value of the given MOT test type field.
     *
     * @param MotTest $motTest
     *
     * @return string Internal MOT test type code from MotTestType
     */
    protected function getMotTestType($motTest)
    {
        $type = null;

        if ($motTest) {
            $testType = $motTest->getMotTestType();

            if ($testType) {
                $type = $testType->getCode();
            }
        }
        return $type;
    }

    /**
     * Based on MOT test certificate number returns common MOT test data
     *
     * @return JsonModel
     */
    public function getMotTestByNumberAction()
    {
        $number = $this->params()->fromQuery("number");

        return $this->get($number);
    }

    /**
     * Based on MOT test ID and optional variation, return the relevant
     * document identifiers and certificate names (VT20, VT30 etc)
     *
     * @return JsonModel
     */
    public function getCertificateDetailsAction()
    {
        $motTestNumber    = $this->params()->fromRoute('motTestNumber');
        $variation = $this->params()->fromRoute('variation', null);

        $motTest = $this->getMotTestService()->getMotTestData($motTestNumber);

        $content = self::getCertificateDetailsContent(
            $motTest,
            $variation,
            $this->getMotTestService(),
            $this->getDocumentService()
        );

        return ApiResponse::jsonOk($content);
    }

    /**
     * This will obtain the Jasper document id(s) of the relevant paperwork for the given test
     * and also obtain the relevant Jasper document template type for making the request to
     * Jasper. We also need to indicate if the certificate is a replacement or the original.
     *
     * @param MotTestDto      $motTest
     * @param string          $variation
     * @param MotTestService  $motService
     * @param DocumentService $documentService
     *
     * @return array
     */
    public static function getCertificateDetailsContent(
        MotTestDto $motTest,
        $variation,
        MotTestService $motService,
        DocumentService $documentService
    ) {
        $testId = $motTest->getId();

        $details = [];
        $certificateIds = $motService->getCertificateIds($motTest);

        if (count($certificateIds)) {
            // Check whether this is a replacement certificate or not
            $isReplacement = ($motService->getReplacementCertificate($testId) !== null);

            foreach ($certificateIds as $id) {
                $reportName = $documentService->getReportName($id, $variation);

                $details[] = [
                    'documentId'    => $id,
                    'reportName'    => $reportName,
                    'isReplacement' => $isReplacement
                ];
            }
        }

        return $details;
    }

    /**
     * Returns whether or not there is an existing in progress test for a vehicle id.
     * Expected params:
     *  route: id - vehicle id
     *
     * TODO extract to mot test - vehicle part
     *
     * @return JsonModel
     */
    public function isTestInProgressAction()
    {
        $vehicleId = $this->params()->fromRoute('id');
        $isPresent = $this->getMotTestService()->isTestInProgressForVehicle($vehicleId);

        return ApiResponse::jsonOk($isPresent);
    }

    /**
     * @return MotTestService
     */
    private function getMotTestService()
    {
        return $this->getServiceLocator()->get('MotTestService');
    }

    /**
     * @return DocumentService
     */
    private function getDocumentService()
    {
        return $this->getServiceLocator()->get('DocumentService');
    }

    /**
     * @return SiteService
     */
    private function getVehicleTestingStationService()
    {
        return $this->getServiceLocator()->get(SiteService::class);
    }
}
