<?php

namespace MailerApi\Validator;

use DvsaCommon\Dto\Mailer\MailerDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaMotApi\Service\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Ensures the type is valid, if not throws a bad command exception.
 */
class MailerValidator
{
    protected $serviceManager;

    const ERROR_UNKNOWN_TYPE = 'Unknown mail request type';
    const ERROR_NO_USERID = 'Username reminder requires a user-id';
    const ERROR_INVALID_DTO = 'DTO object was null';

    /** Code to generate a username reminder by email */
    const TYPE_REMIND_USERNAME = 1;

    /** Code to generate a password reminder by email */
    const TYPE_REMIND_PASSWORD = 2;

    /** Code to generate a reclaim account by email */
    const TYPE_RECLAIM_ACCOUNT = 3;

    /** @var UserService $userService */
    protected $userService;


    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }

    /**
     * Designated validation entry point.
     *
     * @param MailerDto $dto contains the JSON as a DTO instance
     * @param integer $type contains the request context type
     *
     * @return bool TRUE on exit, otherwise it would have failed with an exception.
     * @throws BadRequestException
     */
    public function validate(MailerDto $dto, $type)
    {
        switch ($type) {
            case self::TYPE_REMIND_USERNAME:
                return $this->validateUsernameReminder($dto);

            case self::TYPE_REMIND_PASSWORD:
                return $this->validatePasswordReminder($dto);

            case self::TYPE_RECLAIM_ACCOUNT:
                return $this->validateReclaimAccount($dto);

            default:
                throw new BadRequestException(
                    self::ERROR_UNKNOWN_TYPE,
                    BadRequestException::BAD_REQUEST_STATUS_CODE
                );
        }
    }

    /**
     * Check that the username reminder contains the correct information.
     *
     * @param MailerDto $dto
     *
     * @return bool
     * @throws BadRequestException
     */
    protected function validateUsernameReminder(MailerDto $dto)
    {
        $this->ensureUserIdPresent($dto);

        return true;
    }

    /**
     * Check that the password reminder contains the correct information.
     *
     * @param MailerDto $dto
     *
     * @return bool
     * @throws BadRequestException
     */
    protected function validatePasswordReminder(MailerDto $dto)
    {
        $this->ensureUserIdPresent($dto);

        return true;
    }

    /**
     * Check that the reclaim account contains the correct information.
     *
     * @param MailerDto $dto
     *
     * @return bool
     * @throws BadRequestException
     */
    protected function validateReclaimAccount(MailerDto $dto)
    {
        $this->ensureUserIdPresent($dto);

        return true;
    }

    /**
     * Check that required data for a username reminder is present.
     * If we locate the user then we will add a new field into the
     * DTA data field called 'user', this contains the Person data
     * which can be used to personalise the outgoing message.
     *
     * @param MailerDto $dto
     *
     * @throws BadRequestException
     */
    protected function ensureUserIdPresent(MailerDto $dto)
    {
        $data = $dto->getData();
        $userid = ArrayUtils::tryGet($data, 'userid', null);

        if (is_null($userid)) {
            throw new BadRequestException(
                self::ERROR_NO_USERID,
                BadRequestException::BAD_REQUEST_STATUS_CODE
            );
        }

        $userData = $this->userService->findPerson($userid);

        $data['user'] = $userData;
        $dto->setData($data);
    }
}
