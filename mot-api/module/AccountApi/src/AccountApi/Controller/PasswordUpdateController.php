<?php

namespace AccountApi\Controller;

use AccountApi\Service\TokenService;
use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\NotFoundException;
use Zend\View\Model\JsonModel;

/**
 * Class PasswordUpdateController
 */
class PasswordUpdateController extends AbstractDvsaRestfulController
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
     * update password for the user without a token
     *
     * @param array $data
     * @throws NotFoundException
     * @throws \Exception
     *
     * @return JsonModel
     *
     */
    public function update($userId, $data)
    {
        if (empty($data['password'])) {
            throw new NotFoundException('password');
        }

        $response = $this->tokenService->updatePassword(
            $userId,
            $data['password']
        );
        $this->entityManager->flush();
        return ApiResponse::jsonOk($response);
    }
}
