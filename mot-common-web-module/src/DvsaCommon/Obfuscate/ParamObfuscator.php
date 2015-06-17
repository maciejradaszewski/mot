<?php

namespace DvsaCommon\Obfuscate;

use Zend\Crypt\Exception\InvalidArgumentException as ZendCryptInvalidArgumentException;

/**
 * Class ParamObfuscator.
 */
class ParamObfuscator
{
    const ENCODED_CHAR_COUNT = 144;

    const ENTRY_VEHICLE_ID = 'vehicleId';

    /** @var ParamEncrypter */
    protected $paramEncrypter;
    /** @var ParamEncoder */
    protected $paramEncodor;
    /** @var array */
    protected $entries;

    /**
     * @param ParamEncrypter $paramEncrypter
     * @param ParamEncoder   $paramEncoder
     * @param Array          $config
     */
    public function __construct(
        ParamEncrypter $paramEncrypter,
        ParamEncoder $paramEncoder,
        Array $config
    ) {
        $this->paramEncrypter = $paramEncrypter;
        $this->paramEncodor   = $paramEncoder;

        $this->entries = (
            isset($config['security']['obfuscate']['entries'])
            ? $config['security']['obfuscate']['entries']
            : []
        );
    }

    /**
     * Obfuscate value by condition described in configuration for specified Entry
     *
     * @param string $entryKey
     * @param mixed  $param
     *
     * @return string
     */
    public function obfuscateEntry($entryKey, $param)
    {
        if (!$this->isNeedObfuscateEntry($entryKey)) {
            return $param;
        }

        $this->isEmpty($param);
        // First we encrypt
        $param = $this->getParamEncrypter()->encrypt($param);
        // Then we encode
        $param = $this->getParamEncoder()->encode($param);

        // And here we go
        return $param;
    }

    /**
     * @return string
     */
    public function obfuscate($param)
    {
        return $this->obfuscateEntry(null, $param);
    }

    /**
     * Obfuscate value by condition described in configuration for specified Entry
     *
     * The parameter $fallbackToOriginalValue allows for a mixed behaviour of parsing both obfuscated and non-obfuscated
     * ids. If set to false strict mode will be enabled and an Exception thrown.
     *
     * @param string $entryKey
     * @param mixed  $obfuscatedValue
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function deobfuscateEntry($entryKey, $obfuscatedValue)
    {
        if (!$this->isNeedObfuscateEntry($entryKey)) {
            return $obfuscatedValue;
        }

        $this->isEmpty($obfuscatedValue);

        // First we decode.
        $decodedValue = $this->getParamEncoder()->decode($obfuscatedValue);

        if (!$decodedValue) {
            throw new InvalidArgumentException(
                sprintf(
                    "Trying to deobfuscate something that wasn't obfuscated. Value: '%s', type: %s",
                    $obfuscatedValue,
                    gettype($obfuscatedValue)
                )
            );
        }

        // Then we decrypt.
        try {
            $decryptedValue = $this->getParamEncrypter()->decrypt($decodedValue);
            $e = null;
        } catch (ZendCryptInvalidArgumentException $e) {
            $decryptedValue = null;
        }

        if (!$decryptedValue) {
            throw new InvalidArgumentException(
                $e
                ? $e->getMessage()
                : sprintf(
                    "Trying to deobfuscate something that wasn't obfuscated. Value: '%s', type: %s",
                    $obfuscatedValue,
                    gettype($obfuscatedValue)
                )
            );
        }

        return $decryptedValue;
    }

    /**
     * @return string
     */
    public function deobfuscate($obfuscatedValue, $fallbackToOriginalValue = true)
    {
        return $this->deobfuscateEntry(null, $obfuscatedValue, $fallbackToOriginalValue);
    }

    /**
     * This can be accessible if required.
     *
     * @return ParamEncrypter
     */
    public function getParamEncrypter()
    {
        return $this->paramEncrypter;
    }

    /**
     * This can be accessible if required.
     *
     * @return ParamEncoder
     */
    public function getParamEncoder()
    {
        return $this->paramEncodor;
    }

    /**
     * @param mixed $param
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    protected function isEmpty($param)
    {
        if (null !== $param && !empty($param)) {
            return true;
        }

        throw new InvalidArgumentException('The parameter cannot be empty.');
    }

    private function isNeedObfuscateEntry($entryKey)
    {
        $entry = isset($this->entries[$entryKey]) ? $this->entries[$entryKey] : null;

        if ($entryKey !== null && $entry === null) {
            throw new InvalidArgumentException('The entry key was not found in configuration file.');
        }

        return ($entry === null || $entry === true);
    }
}
