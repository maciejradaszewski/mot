<?php

namespace DvsaCommon\Obfuscate;

class EncryptionKey
{

    /**
     * @var string
     */
    private $key;

    public function __construct($key)
    {
        $this->validate($key);
        $this->key = $key;
    }

    /**
     * @param $key
     * @return bool
     */
    private function validate($key)
    {
        if (empty($key) || !is_string($key)) {
            throw new \Exception('This key is not valid');
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->key;
    }
}
