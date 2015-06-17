<?php

namespace Api\Check;


/**
 * An aggregate for messages of type CheckMessage. Exposes querying capabilities
 * to help investigate a status of an operation.
 *
 * CheckResult::ok() - creates a default positive result i.e. no messages occurred
 *
 * Class CheckResult
 *
 * @package Api\Check
 */
class CheckResult
{
    /**
     * @var CheckMessage[]
     */
    private $messages = [];

    /**
     * Private constructor to enable fluent interface
     */
    private function __construct()
    {
    }

    /**
     * Factory method accepting arguments of type CheckMessage
     * like ::with(CheckMessage::ok()->..., CheckMessage::with(...),...)
     *
     * @return CheckResult
     */
    public static function with()
    {
        $result = CheckResult::ok();
        $checkMessages = func_get_args();
        foreach ($checkMessages as $cm) {
            $result->add($cm);
        }
        return $result;
    }

    /**
     * Factory method creating positive CheckResult
     *
     * @return CheckResult
     */
    public static function ok()
    {
        return new CheckResult();
    }

    /**
     * Adds a message to a result
     *
     * @param CheckMessage $message
     *
     * @return $this
     */
    public function add(CheckMessage $message)
    {
        $this->messages[] = $message;
        return $this;
    }

    /**
     * Returns an array of all messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Retrieves all messages for a given field
     *
     * @param string $field
     *
     * @return CheckMessage[]
     */
    public function getMessagesForField($field)
    {
        return $this->filterMessages(
            function (CheckMessage $message) use (&$field) {
                return $message->getField() === $field;
            }
        );
    }

    /**
     * Checks if a message with the specified text exists
     *
     * @param string $text
     *
     * @return bool
     */
    public function messageWithTextExists($text)
    {
        $messages = $this->filterMessages(
            function (CheckMessage $message) use ($text) {
                return $message->getText() === $text;
            }
        );
        return !empty($messages);
    }

    /**
     * Transforms stored messages to array of message texts (strings)
     *
     * @return array
     */
    public function toArrayOfTexts()
    {
        $arr = [];
        foreach ($this->messages as $m) {
            $arr[] = $m->getText();
        }
        return $arr;
    }

    /**
     * Returns true if no messages are stored
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->messages);
    }

    /**
     * Returns an array of messages of the specified severity. Accepts varargs of members of Severity enum
     *
     * @return CheckMessage[]
     */
    public function getMessagesOfSeverity()
    {
        $severityArray = func_get_args();
        return $this->filterMessages(
            function (CheckMessage $message) use (&$severityArray) {
                return in_array($message->getSeverity(), $severityArray);
            }
        );
    }

    /**
     * Returns an array of messages satisfying given criteria
     *
     * @param callable $filterPredicate
     *      predicate that decides whether a message matches criteria
     *      boolean function(CheckMessage $message)
     *
     * @return CheckMessage[]
     */
    public function filterMessages(callable $filterPredicate)
    {
        $result = [];
        foreach ($this->messages as $message) {
            if ($filterPredicate($message)) {
                $result[] = $message;
            }
        }
        return $result;
    }

    public function __toString()
    {
        /**
         * @var CheckMessage $message
         */
        if (!empty($this->messages)) {
            $description = "";
            foreach ($this->messages as $message) {
                $description .= "Message: {";
                if ($message->getField()) {
                    $description .= "[field=" . $message->getField() . "] ";
                }
                if ($message->getCode()) {
                    $description .= "[code=" . $message->getCode() . "] ";
                }
                if ($message->getSeverity()) {
                    $description .= "[severity=" . $message->getSeverity() . "] ";
                }
                if ($message->getText()) {
                    $description .= "[text=" . $message->getText() . "]";
                }
                $description .= "}\n";
            }
        } else {
            $description = "No messages";
        }
        return $description;
    }
}
