<?php

namespace MailerApi\Service;

use DvsaCommon\Dto\Mailer\MailerDto;
use MailerApi\Validator\MailerValidator;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Exception\BadRequestException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail;
use Zend\Validator\EmailAddress;

/**
 * Class MailerEngine
 *
 * General purpose way to send outgoing emails from the application.
 *
 * This class is NOT responsible for the content, subject or recipient
 * values, that is the callers responsibility.
 */
class MailerService
{
    const ERROR_TYPE_STRING = 'Expected a string for ';
    const ERROR_TYPE_LENGTH = 'Length must be non-zero for ';

    /** @var  Array contains mailer configuration data */
    protected $config;

    /** The application logging service */
    protected $logger;

    /** The mailer validation handler */
    protected $validator;

    /** Default from address for sent emails */
    const DEFAULT_FROM = 'noreply@dvsa-helpdesk';


    /**
     * The mail engine. A reusable class for sending emails using Zend.
     *
     * @param Array $config contains the system-wide configuration data
     * @param Object $logger contains the application logger instance
     * @param MailerValidator|Object $validator contains the Mail service validation handler
     */
    public function __construct($config, $logger, MailerValidator $validator)
    {
        $this->config = ArrayUtils::tryGet($config, 'mailer', []);
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * @param MailerDto $dto
     * @param $type
     * @return bool
     * @throws BadRequestException
     */
    public function validate(MailerDto $dto, $type)
    {
        return $this->validator->validate($dto, $type);
    }

    /**
     * Send the mail with the formatted body content.
     *
     * We consult the mailer configuration for direction on how to log and
     * what values to use in certain parts of the email.
     *
     * @param $recipient string the recipient email(s) to send to.
     * @param $subject   string the subject line of the email.
     * @param $message   string the full email body content.
     *
     * @return bool TRUE if the mail was successfully spooled
     */
    public function send($recipient, $subject, $message)
    {
        $this->mustBeGiven($recipient, 'recipient')
            ->mustBeGiven($subject, 'subject')
            ->mustBeGiven($message, 'message');

        if (!ArrayUtils::tryGet($this->config, 'sendingAllowed', true)) {
            return false;
        }

        $mailFrom = ArrayUtils::tryGet($this->config, 'sender', self::DEFAULT_FROM);
        list($actualRecipient, $override) = $this->getActualRecipient($recipient);

        if ($this->logger) {
            if ($override) {
                $this->logger->info(sprintf('TO(override): %s, SUBJECT: %s', $actualRecipient, $subject));
            } else {
                $this->logger->info(sprintf('TO: %s, SUBJECT: %s', $actualRecipient, $subject));
            }
        }

        // Hint: Chaining is broken by mocked objects, don't "refactor" this please.
        $mail = $this->getMailInstance();
        $mail->setBody($message);
        $mail->setFrom($mailFrom);
        $mail->addTo($actualRecipient);
        $mail->setSubject($subject);

        $transport = $this->getTransportInstance();

        try {
            $transport->send($mail);

            return true;

        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->err(
                    sprintf(
                        'TO: %s, SUBJECT: %s, FAIL: %s',
                        $recipient,
                        $subject,
                        $e->getMessage()
                    )
                );
            }
        }
        return false;
    }

    /**
     * Using configuration data if present, work out who is the real recipient
     * of the mail we are trying to send.
     *
     * @param $tentativeRecipient string contains the intended recipient
     *
     * @return Array [String,bool] => the actual email recipient and TRUE if
     *                an overriding of the $tentativeRecipient took place.
     * @throws \Exception
     */
    private function getActualRecipient($tentativeRecipient)
    {
        $actualRecipient = ArrayUtils::tryGet($this->config, 'recipient', $tentativeRecipient);
        $emailValidator = new EmailAddress();

        if (!$emailValidator->isValid($actualRecipient)) {
            $errorText = sprintf('MailerEngine::send: invalid email: %s', $actualRecipient);
            if ($this->logger) {
                $this->logger->err($errorText);
            }
            throw new \Exception($errorText);
        }
        return [$actualRecipient, $actualRecipient != $tentativeRecipient];
    }

    /**
     * Returns an instance of a class to compose a message. By default we use the Zend mail class
     * but we check the configuration file as well. The configuration can specify an actual class
     * name (string) or an instance to be used (is_object). Useful for mock testing too.
     *
     * @return Object instance of a class to encode a mail message
     * @throws \Exception if an instance cannot be returned
     */
    protected function getMailInstance()
    {
        $mailClass = ArrayUtils::tryGet($this->config, 'mail-class', Mail\Message::class);
        $mailInstance = null;

        if (is_object($mailClass)) {
            $mailInstance = $mailClass;
        } elseif (class_exists($mailClass)) {
            $mailInstance = new $mailClass ();
        } else {
            $errorText = sprintf('MailerEngine::getMailInstance: no class loaded for %s', $mailClass);
            if ($this->logger) {
                $this->logger->err($errorText);
            }
            throw new \Exception($errorText);
        }
        return $mailInstance;
    }


    /**
     * Returns an instance of a class to send a message.
     *
     * @return mixed instance of a class to send a mail message
     * @throws \Exception
     */
    protected function getTransportInstance()
    {
        $mtaClass = ArrayUtils::tryGet($this->config, 'mta-class', Mail\Transport\Sendmail::class);
        $transportInstance = null;

        if (is_object($mtaClass)) {
            $transportInstance = $mtaClass;
        } else {
            if (class_exists($mtaClass)) {
                $transportInstance = new $mtaClass ();
            } else {
                $errorText = sprintf('MailerEngine::getTransportInstance: no class loaded for %s', $mtaClass);
                if ($this->logger) {
                    $this->logger->err($errorText);
                }
                throw new \Exception($errorText);
            }
        }
        return $transportInstance;
    }


    /**
     * Ensure the arguments to send() are well formed and of the correct types.
     *
     * @param $value String contains the value to be tested
     * @param $label String contains the context for an error message
     *
     * @return $this
     * @throws BadRequestException
     */
    protected function mustBeGiven($value, $label)
    {
        if (!is_string($value)) {
            throw new BadRequestException(
                self::ERROR_TYPE_STRING . $label,
                BadRequestException::BAD_REQUEST_STATUS_CODE
            );
        }

        if (!strlen(trim($value))) {
            throw new BadRequestException(
                self::ERROR_TYPE_LENGTH . $label,
                BadRequestException::BAD_REQUEST_STATUS_CODE
            );
        }

        return $this;
    }
}
