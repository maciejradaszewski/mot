<?php

namespace MailerApi\Logic;

use DvsaCommon\Dto\Mailer\MailerDto;
use MailerApi\Service\MailerService;
use MailerApi\Validator\MailerValidator;
use UserApi\Person\Service\PersonalDetailsService;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UsernameReminder
 *
 * All business logic for sending a username reminder is inside here.
 *
 * @package MailerApi\Logic
 */
class UsernameReminder extends Reminder
{
    /**
     * Constructs the user name reminder logic controller. Here we trigger validation
     * of the request (in the parent) and then do what is required to cause an email
     * to be sent to the requesting user.
     *
     * @param Array $mailerConfig
     * @param Array $helpdeskConfig
     * @param $mailerService MailerService
     * @param $personalDetailsService $personalDetailsService
     * @param $dto MailerDto
     */
    public function __construct(
        Array $mailerConfig,
        Array $helpdeskConfig,
        MailerService $mailerService,
        PersonalDetailsService $personalDetailsService,
        MailerDto $dto
    ) {
        parent::__construct(
            $mailerConfig,
            $helpdeskConfig,
            $mailerService,
            $personalDetailsService,
            $dto,
            MailerValidator::TYPE_REMIND_USERNAME
        );
    }

    /**
     * Attempts to deliver a message to a mail subsystem.
     *
     * @param Array $data Contains data for template expansion
     *
     * @return bool TRUE of the message was accepted by the mail sub-system.
     *              This does NOT mean the message has been delivered yet.
     */
    public function send(Array $data = [])
    {
        $subject = $this->renderTemplate(
            $this->dto,
            'username',
            'reminder-subject'
        );

        $message = $this->renderTemplate(
            $this->dto,
            'username',
            'reminder',
            $data
        );

        return $this->mailService->send(
            $this->getRecipient($this->dto),
            $subject,
            $message
        );
    }
}
