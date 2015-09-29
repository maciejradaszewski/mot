<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Controller;

use Dvsa\Mot\Api\RegistrationModule\Service\DuplicatedEmailChecker;
use Dvsa\Mot\Api\RegistrationModule\Service\RegistrationService;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\BadRequestException;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractDvsaRestfulController
{
    const KEY_EMAIL = 'email';
    const ERR_MSG_MISSING_KEY = 'Expected "%s" key is missing';

    /**
     * @var RegistrationService
     */
    private $registrationService;

    /**
     * @var DuplicatedEmailChecker
     */
    private $duplicatedEmailChecker;

    public function __construct(
        RegistrationService $registrationService,
        DuplicatedEmailChecker $duplicatedEmailChecker
    )
    {
        $this->registrationService = $registrationService;
        $this->duplicatedEmailChecker = $duplicatedEmailChecker;
    }

    /**
     * @param mixed $data
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function create($data)
    {
        if ($this->registrationService->register($data)) {
            return ApiResponse::jsonOk(
                [
                    'registeredPerson' => [
                        'id' => $this->registrationService->getRegisteredPerson()->getId(),
                        'username' => $this->registrationService->getRegisteredPerson()->getUsername(),
                    ],
                ]
            );
        }

        $this->getResponse()->setStatusCode(\Zend\Http\Response::STATUS_CODE_422);

        return ApiResponse::jsonError(
            [$this->registrationService->getMessages()]
        );
    }

    /**
     * @return \Zend\View\Model\JsonModel
     * @throws BadRequestException
     */
    public function checkDuplicatedEmailAction()
    {
        $content = json_decode($this->getRequest()->getContent(), true);

        if (!array_key_exists(self::KEY_EMAIL, $content)) {
            throw new BadRequestException(
                sprintf(self::ERR_MSG_MISSING_KEY, self::KEY_EMAIL)
            );
        }

        $email = $content[self::KEY_EMAIL];

        return ApiResponse::jsonOk(
            [
                'isExists' => $this->duplicatedEmailChecker->isEmailDuplicated($email)
            ]
        );
    }
}
