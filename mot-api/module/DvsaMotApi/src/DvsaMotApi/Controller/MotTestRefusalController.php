<?php

namespace DvsaMotApi\Controller;

use DvsaCommon\Auth\Assertion\RefuseToTestAssertion;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaMotApi\Service\CertificateCreationService;
use OrganisationApi\Service\Mapper\PersonMapper;
use SiteApi\Service\SiteService;
use VehicleApi\Service\VehicleService;

/**
 * Class MotTestRefusalController
 * @package DvsaMotApi\Controller
 */
class MotTestRefusalController extends AbstractDvsaRestfulController implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    /**
     * @param array $data
     * @return void|\Zend\View\Model\JsonModel
     */
    public function create($data)
    {
        // ensure data is array.
        $data = (array) $data;

        $identity = $this->getIdentity();

        // Create the input vars from the post data
        $vehicleId = $data['vehicleId'];

        $vtsData = $this->getVtsData($data['siteId']);

        $authorisationService = $this->serviceLocator->get('DvsaAuthorisationService');
        $refuseToTestAssertion = new RefuseToTestAssertion($authorisationService);
        $refuseToTestAssertion->assertGranted($data['siteId']);

        //  --   Get vehicle data    --
        $vehicleService = $this->getServiceLocator()->get(VehicleService::class);
        /** @var \DvsaCommon\Dto\Vehicle\VehicleDto $vehicle */
        $vehicle = $vehicleService->getVehicleDto($vehicleId);

        //  --  Get reason for cancel   --
        $rfrId = ArrayUtils::tryGet($data, 'rfrId');
        $reasonForRefusal = current($this->getCatalog()->getReasonsForRefusal(['id' => $rfrId]));

        //  --  Create the data for the mapper  --
        $person = $identity->getPerson();
        $mapperData = (new MotTestDto())
            ->setVehicle($vehicle)
            ->setTester((new PersonMapper())->toDto($person))
            ->setReasonForCancel($reasonForRefusal)
            ->setIssuedDate(DateUtils::nowAsUserDateTime())
            ->setVehicleTestingStation(
                [
                    'name'         => ArrayUtils::tryGet($vtsData, 'name', ''),
                    'address'      => ArrayUtils::tryGet($vtsData, 'address', ''),
                    'siteNumber'   => ArrayUtils::tryGet($vtsData, 'siteNumber', ''),
                    'dualLanguage' => ArrayUtils::tryGet($vtsData, 'dualLanguage', false),
                ]
            );

        //  -- Create the snapshot  --
        /** @var CertificateCreationService $certificateCreationService */
        $certificateCreationService = $this->getServiceLocator()->get(CertificateCreationService::class);
        $result = $certificateCreationService->createFailCertificate(
            null, $mapperData, $this->getUserId()
        );

        $responseData = [
            'documentId' => $result->getDocument(),
        ];

        return ApiResponse::jsonOk($responseData);
    }

    /**
     * Get the Vehicle Testing Station data.
     *
     * @param int $vtsId the vehicle testing station ID
     * @return mixed this will return an associative array if VTS exists, false
     * otherwise
     */
    protected function getVtsData($vtsId)
    {
        if (is_int($vtsId) && $vtsId > 0) {
            /** @var SiteService $service */
            $service = $this->getServiceLocator()->get(SiteService::class);
            return $service->getVehicleTestingStationData($vtsId);
        }

        return false;
    }

    /**
     * @return \DvsaDocument\Service\Document\DocumentService
     */
    protected function getDocumentService()
    {
        $service = $this->getServiceLocator()->get('DocumentService');
        $service->setServiceLocator($this->getServiceLocator());
        return $service;
    }
}
