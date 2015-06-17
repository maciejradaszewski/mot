<?php

namespace UserApi\Person\Controller;

use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
/**
 * Provides the current users's roles and permissions to the web tier.
 */
class PersonAuthorisationController extends AbstractDvsaRestfulController
{
    public function get($id)
    {
        /** @var MotIdentityInterface $identity */
        $identity = $this->getIdentity();

        if (is_null($identity)) {
            return ApiResponse::jsonError("Not logged in");
        }

        if (!($identity->getUserId() == $id)) {
            throw new UnauthorisedException(
                "Cannot retrieve authorization for a different user [" . $identity->getUserId() . "] vs [" . $id . "]"
            );
        }

        /** @var AuthorisationServiceInterface $motAuthorizationService */
        $motAuthorizationService = $this->getServiceLocator()->get('DvsaAuthorisationService');
        return ApiResponse::jsonOk($motAuthorizationService->getAuthorizationDataAsArray());
    }
}
