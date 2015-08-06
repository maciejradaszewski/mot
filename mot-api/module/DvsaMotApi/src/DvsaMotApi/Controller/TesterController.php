<?php

namespace DvsaMotApi\Controller;

use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaMotApi\Service\TesterService;
use Zend\View\Model\JsonModel;

/**
 * Class TesterController
 *
 * @package DvsaMotApi\Controller
 */
class TesterController extends AbstractDvsaRestfulController
{
    const QUERY_PARAM_USER_ID = 'userId';
    const QUERY_PARAM_CERT_NUMBER = 'certificateNumber';

    /** @var TesterService */
    private $testerService;

    /**
     * @param TesterService $testerService
     */
    public function __construct(TesterService $testerService)
    {
        $this->testerService = $testerService;
    }

    public function get($id)
    {
        $this->testerService->verifyAndApplyTesterIsActiveByTesterId($id);
        $testerData = $this->testerService->getTesterData($id);

        return ApiResponse::jsonOk($testerData);
    }

    public function getList()
    {
        $userId = $this->params()->fromQuery(self::QUERY_PARAM_USER_ID);
        $certificateNumber = $this->params()->fromQuery(self::QUERY_PARAM_CERT_NUMBER);

        $testerData = null;
        if ($userId) {
            $this->testerService->verifyAndApplyTesterIsActiveByUserId($userId);
            $testerData = $this->testerService->getTesterDataByUserId($userId);
        } elseif ($certificateNumber) {
            $testerData = $this->testerService->findTesterDataByCertificateNumber($certificateNumber);
        }
        return ApiResponse::jsonOk($testerData);
    }

    /**
     * SWG\Api(
     *  path="/tester/{id}/vehicle-testing-stations"
     * )
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getVehicleTestingStationsAction()
    {
        $testerId = $this->params()->fromRoute('id');
        $data     = $this->testerService->getVehicleTestingStationsForTester($testerId);

        return ApiResponse::jsonOk($data);
    }

    public function getInProgressTestIdAction()
    {
        $personId = $this->params()->fromRoute('id');
        $testId   = $this->testerService->findInProgressTestIdForTester($personId);

        return ApiResponse::jsonOk($testId);
    }

    public function getVtsWithSlotBalanceAction()
    {
        $testerId = $this->params()->fromRoute('id');
        $data = $this->testerService->getTesterData($testerId, true);

        return ApiResponse::jsonOk($data);
    }

}
