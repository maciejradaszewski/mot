<?php

namespace DvsaCommon\Obfuscate;

use Zend\Crypt\BlockCipher;

class ParamEncrypter
{
    const METHOD = 'RC4';

    private $ivLen;
    private $encryptKey;

    public function __construct(EncryptionKey $key)
    {
        if (!extension_loaded('openssl')) {
            throw new \Exception('open ssl module not loaded');
        }

        if (!in_array(self::METHOD, openssl_get_cipher_methods())) {
            throw new \Exception('open ssl method is not supported');
        }

        $this->ivLen = openssl_cipher_iv_length(self::METHOD);

        $this->encryptKey = $key->getValue();
    }

    /**
     * Needed to convert to string as BlockCipher does not validate integers.
     *
     * @param $id
     * @return string
     */
    public function encrypt($param)
    {
        $iv = openssl_random_pseudo_bytes($this->ivLen);

        $result = openssl_encrypt($param, self::METHOD, $this->encryptKey, OPENSSL_RAW_DATA, $iv);

        return $iv.$result;
    }

    /**
     * Needed to convert to string as BlockCipher does not validate integers.
     *
     * @param $encryption
     * @return bool|string
     */
    public function decrypt($param)
    {
        if (strlen($param) < $this->ivLen) {
            throw new \Zend\Crypt\Exception\InvalidArgumentException('value too short');
        }

        $iv = substr($param, 0, $this->ivLen);
        $data = substr($param, $this->ivLen);

        return openssl_decrypt($data, self::METHOD, $this->encryptKey, OPENSSL_RAW_DATA, $iv);
    }
}
