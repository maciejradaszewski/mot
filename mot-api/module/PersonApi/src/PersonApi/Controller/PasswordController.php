<?php

namespace PersonApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use PersonApi\Service\PasswordService;

class PasswordController extends AbstractDvsaRestfulController
{
    private $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    public function update($id, $data)
    {
        $this->passwordService->changePassword((int) $id, $data);

        return ApiResponse::jsonOk();
    }
}
