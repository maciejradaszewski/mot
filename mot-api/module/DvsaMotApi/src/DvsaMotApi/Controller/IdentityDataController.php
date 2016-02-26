<?php

namespace DvsaMotApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;
use DvsaAuthentication\Identity;


class IdentityDataController extends AbstractDvsaRestfulController
{
    public function getList()
    {
        /** @var ServiceLocatorInterface $sm */
        $sm = $this->getServiceLocator();
        /** @var AuthenticationService $motIdentityProvider */
        $motIdentityProvider = $sm->get('DvsaAuthenticationService');
        /** @var Identity $identity */
        $identity = $motIdentityProvider->getIdentity();

        $personData = [
            'userId'                   => $identity->getUserId(),
            'username'                 => $identity->getUsername(),
            'displayName'              => $identity->getDisplayName(),
            'role'                     => '',
            'isAccountClaimRequired'   => $identity->isAccountClaimRequired(),
            'isPasswordChangeRequired' => $identity->isPasswordChangeRequired(),
            'isSecondFactorRequired'   => $identity->isSecondFactorRequired()
        ];
        $returnStruct['user'] = $personData;
        $returnStruct['identity'] = $identity->getUsername();

        return ApiResponse::jsonOk($returnStruct);
    }
}
