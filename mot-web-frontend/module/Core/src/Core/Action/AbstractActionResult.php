<?php

namespace Core\Action;

abstract class AbstractActionResult
{
    private $errorMessages = [];
    private $successMessages = [];

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    public function addErrorMessage($error)
    {
        $this->errorMessages[] = $error;
        return $this;
    }

    public function addErrorMessages(array $errors)
    {
        foreach ($errors as $error) {
            $this->addErrorMessage($error);
        }
        return $this;
    }

    public function getSuccessMessages()
    {
        return $this->successMessages;
    }

    public function addSuccessMessage($successMessage)
    {
        $this->successMessages[] = $successMessage;
        return $this;
    }

    public function addSuccessMessages(array $successMessages)
    {
        foreach ($successMessages as $successMessage) {
            $this->addErrorMessage($successMessage);
        }
        return $this;
    }
}
