<?php

namespace UserApi\Message\Service\Validator;

use DvsaCommon\Enum\MessageTypeCode;
use DvsaCommonApi\Service\Exception\RequiredFieldException;
use DvsaCommonApi\Service\Validator\AbstractValidator;

class MessageValidator extends AbstractValidator
{
    const ERROR_UNSUPPORTED_MESSAGE_TYPE = "Unsupported message type '%s'";

    private $requiredFields = [
        'personId',
        'messageTypeCode',
    ];

    private $supportedMessageTypes = [
        MessageTypeCode::PASSWORD_RESET_BY_LETTER,
        MessageTypeCode::USERNAME_REMINDER_BY_LETTER,
        MessageTypeCode::ACCOUNT_RESET_BY_LETTER,
    ];

    public function validate($data)
    {
        RequiredFieldException::CheckIfRequiredFieldsNotEmpty($this->requiredFields, $data);

        if (false === in_array($data['messageTypeCode'], $this->supportedMessageTypes)) {
            $this->errors->add(
                sprintf(self::ERROR_UNSUPPORTED_MESSAGE_TYPE, $data['messageTypeCode']),
                'messageTypeCode'
            );
        }

        $this->errors->throwIfAny();
    }
}
