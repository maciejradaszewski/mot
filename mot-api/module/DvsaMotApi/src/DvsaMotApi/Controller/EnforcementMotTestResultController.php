<?php

namespace DvsaMotApi\Controller;

use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaMotApi\Service\EnforcementMotTestResultService;
use Zend\View\Model\JsonModel;

/**
 * Class EnforcementMotTestResultController
 *
 * @package DvsaMotApi\Controller
 */
class EnforcementMotTestResultController extends AbstractDvsaRestfulController
{
    public function get($id)
    {
        $result = $this->getService()->getEnforcementMotTestResultData($id);

        return ApiResponse::jsonOk($result);
    }

    public function create($data)
    {
        $result = $this->getService()->createEnforcementMotTestResult(
            $data,
            $this->getUsername()
        );

        return ApiResponse::jsonOk($result);
    }

    public function update($id, $data)
    {
        $result = $this->getService()->updateEnforcementMotTestResult(
            $data,
            $this->getUsername()
        );

        return ApiResponse::jsonOk($result);
    }

    /**
     * @return EnforcementMotTestResultService
     */
    private function getService()
    {
        return $this->getServiceLocator()->get('EnforcementMotTestResultService');
    }
}
