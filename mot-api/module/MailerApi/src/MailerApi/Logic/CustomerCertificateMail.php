<?php

namespace MailerApi\Logic;


use DvsaCommon\Dto\Mailer\MailerDto;
use DvsaCommon\Utility\ArrayUtils;
use MailerApi\Service\MailerService;
use MailerApi\Validator\MailerValidator;

class CustomerCertificateMail
{
    /** @var  MailerService */
    private $mailService;

    /** @var EmailTemplateRenderer */
    private $templateRenderer;

    /** @var array */
    private $templates;

    /**
     * CustomerCertificateMail constructor.
     * @param MailerService $mailService
     */
    public function __construct(MailerService $mailService)
    {
        $this->templates = [
            'subject' => __DIR__ . '/../../../view/email/customer-certificate/subject.phtml',
            'message' => __DIR__ . '/../../../view/email/customer-certificate/message.phtml',
        ];

        $this->mailService = $mailService;
        $this->templateRenderer = new EmailTemplateRenderer($this->templates);
    }

    public function send(MailerDto $dto) {
        $this->mailService->validate($dto, MailerValidator::TYPE_CUSTOMER_CERTIFICATE);
        $data = $dto->getData();

        $recipient =  ArrayUtils::tryGet($data, 'email', null);
        $subject =   $this->templateRenderer->renderTemplate($data, 'subject');
        $message =  $this->templateRenderer->renderTemplate($data, 'message');
        $attachment =  ArrayUtils::tryGet($data, 'attachment', null);

        return $this->mailService->send($recipient, $subject, $message, $attachment);
    }
}