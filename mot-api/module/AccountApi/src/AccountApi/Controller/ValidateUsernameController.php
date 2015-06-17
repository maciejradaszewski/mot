<?php

namespace AccountApi\Controller;

use AccountApi\Service\TokenService;
use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\Person\Service\PersonService;

/**
 *
 * Class ValidateUsernameController
 * @package AccountApi\Controller
 */
class ValidateUsernameController extends AbstractDvsaRestfulController
{
    const QUERY_USERNAME = 'username';

    /** @var PersonService */
    protected $personService;
    /** @var EntityManager */
    protected $entityManager;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    /**
     * This function validate if the username that the user gave is valid
     *
     * @return \Zend\View\Model\JsonModel
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getList()
    {
        $login = $this->getRequest()->getQuery(self::QUERY_USERNAME, '');
        return ApiResponse::jsonOk($this->personService->assertUsernameIsValidAndHasAnEmail($login));
    }
}
