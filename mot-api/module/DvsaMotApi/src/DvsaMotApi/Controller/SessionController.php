<?php
namespace DvsaMotApi\Controller;

use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Identity;
use DvsaAuthentication\Login\LoginService;
use DvsaCommon\Auth\Http\AuthorizationBearer;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\BadRequestException;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
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

    public function deleteList($data)
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

        $username = isset($data['username']) ? $data['username'] : '';
        $password = isset($data['password']) ? $data['password'] : '';

        /**
         * @var ServiceLocatorInterface $sm
         * @var LoginService $loginService
         * */
        $sm = $this->getServiceLocator();
        $loginService = $sm->get(LoginService::class);
        $response = $loginService->login($username, $password);

        return $this->returnDto($response);
    }
}
