<?php

namespace AccountApi\Controller;

use AccountApi\Service\Exception\OpenAmChangePasswordException;
use AccountApi\Service\TokenService;
use Doctrine\ORM\EntityManager;
use DvsaCommon\InputFilter\Account\ChangePasswordInputFilter;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Service\Validator\ErrorSchema;
use Zend\View\Model\JsonModel;

/**
 * Class ClaimController
 */
class PasswordChangeController extends AbstractDvsaRestfulController
{
    /** @var TokenService */
    protected $tokenService;
    /** @var EntityManager */
    protected $entityManager;

    public function __construct(
        TokenService $tokenService,
        EntityManager $entityManager
    ) {
        $this->tokenService = $tokenService;
        $this->entityManager = $entityManager;
    }

    /**
     * update password for the user with validated token
     *
     * @param array $data
     * @throws NotFoundException
     * @throws \Exception
     *
     * @return JsonModel
     *
     */
    public function create($data)
    {
        if (empty($data['token'])) {
            throw new NotFoundException('token');
        }

        if (empty($data['newPassword'])) {
            throw new NotFoundException('newPassword');
        }

        try {
            $response = $this->tokenService->changePassword(
                $data['token'],
                $data['newPassword']
            );

            $this->entityManager->flush();
            return ApiResponse::jsonOk($response);
        } catch (OpenAmChangePasswordException $ex) {
            ErrorSchema::throwError(ChangePasswordInputFilter::MSG_PASSWORD_HISTORY, 'newPassword');

            // The return below will never be reached as the exception above will be thrown.
            // Sadly PhpStorm cannot deduce that and keeps showing a warning
            // that not all code paths have "return" statement.
            // Thus I've put "return null" so it shuts up.
            return null;
        }
    }
}
