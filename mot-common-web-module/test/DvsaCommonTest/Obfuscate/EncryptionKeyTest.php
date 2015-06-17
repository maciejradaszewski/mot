<?php

namespace DvsaCommonApiTest\Obfuscate;

use DvsaCommon\Obfuscate\EncryptionKey;

class EncryptionKeyTest extends \PHPUnit_Framework_TestCase
{
    /** @var  EncryptionKey */
    private $encryptionKey;

    private $key = 'abcd1234';

    private $invalidKey = [
        'test' => 'test'
    ];

    public function setUp()
    {
        $this->encryptionKey = new EncryptionKey($this->key);
    }

    public function testGetValueMatchesKey()
    {
        $this->assertEquals($this->encryptionKey->getValue(), $this->key);
    }

    public function testValidateForPrivateKeyIsValid()
    {
        $encryptionKey = new EncryptionKey($this->key);
        $this->assertNotFalse($encryptionKey);
    }

    /**
     * @expectedException Exception
     */
    public function testValidateForInvalidPrivateKeyIsInvalid()
    {
        $encryptionKey = new EncryptionKey($this->invalidKey);
        return $encryptionKey;
    }

    /**
     * @expectedException Exception
     */
    public function testValidateForEmptyPrivateKeyIsInvalid()
    {
        $encryptionKey = new EncryptionKey('');
        return $encryptionKey;
    }

}
 