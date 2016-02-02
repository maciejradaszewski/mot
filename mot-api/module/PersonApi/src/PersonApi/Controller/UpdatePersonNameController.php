<?php

namespace PersonApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use PersonApi\Service\PersonNameService;

class UpdatePersonNameController extends AbstractDvsaRestfulController
{
    /**
     * @var PersonNameService
     */
    protected $personNameService;

    public function __construct(PersonNameService $personNameService)
    {
        $this->personNameService = $personNameService;
    }

    /**
     * Update a person's name
     * $data should contain 'firstName' and 'lastName'.
     *
     * @param array $data
     *
     * @throws \DvsaCommonApi\Service\Exception\InvalidFieldValueException
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function create($data)
    {
        $personId = $this->params()->fromRoute('id');

        $nameResponse = $this->personNameService->update((int) $personId, $data);

        return ApiResponse::jsonOk($nameResponse);
    }
}
