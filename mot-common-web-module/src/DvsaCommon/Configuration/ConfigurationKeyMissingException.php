<?php

namespace DvsaCommon\Configuration;

class ConfigurationKeyMissingException extends \Exception
{
    public function __construct(array $keys)
    {
        parent::__construct($this->createMessage($keys));
    }

    private function createMessage(array $keys)
    {
        $template = "Parameter %s is missing in configuration.";

        return sprintf($template, $this->keysToString($keys));
    }

    private function keysToString(array $keys)
    {
        return count($keys) == 1 ? "'" . $keys[0] . "''" : $this->multipleKeysAsString($keys);
    }

    private function multipleKeysAsString(array $keys)
    {
        $name = '';

        foreach ($keys as $key) {
            $name .= "['" . $key . "']";
        }

        return $name;
    }
}
