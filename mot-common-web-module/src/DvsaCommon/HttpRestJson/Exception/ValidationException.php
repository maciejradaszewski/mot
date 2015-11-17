<?php

namespace DvsaCommon\HttpRestJson\Exception;

/**
 * Covers HTTP 400 exceptions where the the user did/typed in something wrong
 * and therefore it's recoverable.
 */
class ValidationException extends RestApplicationException
{
    /**
     * @return array
     */
    public function getValidationMessages()
    {
        $errors = $this->getErrors();

        return isset($errors['problem']['validation_messages']) ?
            ['problem']['validation_messages'] : [];
    }
}
