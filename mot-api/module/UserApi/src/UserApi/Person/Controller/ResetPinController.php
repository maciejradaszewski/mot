<?php

namespace UserApi\Person\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use UserApi\Person\Service\PersonService;

/**
 * Class ResetPinController
 *
 * @package UserApi\Person\Controller
 */
class ResetPinController extends AbstractDvsaRestfulController
{
    const FIELD_PASSWORD = 'password';

    public function update($id, $data)
    {
        $service = $this->getServiceLocator()->get(PersonService::class);

        if ((int) $id !== $this->getIdentity()->getUserId()) {
            $this->getResponse()->setStatusCode(\Zend\Http\Response::STATUS_CODE_400);
            return ApiResponse::jsonError(['message' => 'Can only reset your own PIN']);
        }

        $pin = $service->regeneratePinForPerson($id);
        return ApiResponse::jsonOk(['pin' => $pin]);
    }
}
