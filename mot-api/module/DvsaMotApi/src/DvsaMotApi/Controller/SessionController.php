<?php
namespace DvsaMotApi\Controller;

use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiCredentialsBasedAdapter;
use DvsaAuthentication\Authentication\Adapter\UserCredentials;
use DvsaCommon\Auth\Http\AuthorizationBearer;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaMotApi\Service\RoleRefreshService;
use Zend\Authentication\AuthenticationService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;

/**
 * Class SessionController
 *
 * @package DvsaMotApi\Controller
 */
class SessionController extends AbstractDvsaRestfulController
{

    public function deleteList()
    {
        /** @var Request $request */
        $request = $this->serviceLocator->get('request');

        $header = $request->getHeader(AuthorizationBearer::FIELD_NAME);
        if (!$header) {
            throw new BadRequestException('Invalid Bearer token!', BadRequestException::ERROR_CODE_INVALID_DATA);
        }

        try {
            $token = AuthorizationBearer::fromString($header->toString())->getToken();
            /** @var OpenAMClientInterface $openAMClient */
            $openAMClient = $this->serviceLocator->get(OpenAMClientInterface::class);
            $openAMClient->logout($token);
        } catch (\Exception $e) {
            throw new BadRequestException('Invalid Bearer token!', BadRequestException::ERROR_CODE_INVALID_DATA);
        }

        return ApiResponse::jsonOk('Succesful logout');
    }

    public function create($data)
    {
        $errors = [];
        $errors = $this->checkForRequiredFieldAndAddToErrors('username', $data, $errors);
        $errors = $this->checkForRequiredFieldAndAddToErrors('password', $data, $errors);

        if (count($errors) > 0) {
            return $this->returnBadRequestResponseModelWithErrors($errors);
        }

        /**
         * @var ServiceLocatorInterface          $sm
         * @var OpenAMApiCredentialsBasedAdapter $adapter
         * */
        $sm = $this->getServiceLocator();
        $adapter = $sm->get(OpenAMApiCredentialsBasedAdapter::class);
        $adapter->setCredentials($data['username'], $data['password']);

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $sm->get('DvsaAuthenticationService');
        $authenticationService->setAdapter($adapter);
        $result = $authenticationService->authenticate();

        $returnStruct = [
            'accessToken' => null,
            'user'        => null
        ];

        if ($result->isValid()) {
            /** @var \DvsaAuthentication\Identity $identity */
            $identity = $result->getIdentity();

            /** @var RoleRefreshService $roleRefreshService */
            $roleRefreshService = $sm->get('RoleRefreshService');
            if ($roleRefreshService->refreshRoles($identity->getUserId())) {
                // TODO - we need to move this somewhere else when the SessionController is deleted!
            }

            $personData = [
                'userId'      => $identity->getUserId(),
                'username'    => $identity->getUsername(),
                'displayName' => $identity->getPerson()->getDisplayName(),
                'role'        => '',
                'accountClaimRequired' => $identity->getPerson()->isAccountClaimRequired(),
            ];
            $returnStruct['accessToken'] = $identity->getToken();
            $returnStruct['user'] = $personData;
        }
        $returnStruct['code'] = $result->getCode();
        $returnStruct['messages'] = $result->getMessages();
        $returnStruct['isValid'] = $result->isValid();
        $returnStruct['identity'] = $data['username'];

        return ApiResponse::jsonOk($returnStruct);
    }
}
