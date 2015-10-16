<?php

namespace PersonApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use PersonApi\Service\PasswordExpiryService;

class PasswordExpiryController extends AbstractDvsaRestfulController
{
    /**
     * @var PasswordExpiryService
     */
    private $passwordExpiryService;

    public function __construct(PasswordExpiryService $service)
    {
        $this->passwordExpiryService = $service;
    }

    public function create($data)
    {
        $this->passwordExpiryService->notifyUserIfPasswordExpiresSoon($data);

        return ApiResponse::jsonOk();
    }
}
