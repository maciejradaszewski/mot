<?php

namespace DvsaCommonApiTest\Obfuscate;

use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommonTest\Bootstrap;

class ParamEncrypterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ParamEncrypter $paramEncrypter */
    private $paramEncrypter;

    private $sampleId = 123456789;
    private $serviceManager;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();

        $this->serviceManager->setAllowOverride(true);

        // Having to specify key here for security settings mainly
        // because only the API would have these defined.
        $config = $this->serviceManager->get('config');
        $config = array_merge(
            $config, [
                'security' => [
                    'obfuscate' => [
                        'key' => 'abc123456789'
                    ]
                ]
            ]
        );

        $this->serviceManager->setService('config', $config);

        $this->paramEncrypter = $this->serviceManager->get(ParamEncrypter::class);
    }

    public function testEncryptIdDoesNotReturnId()
    {
        $encryptedContent = $this->paramEncrypter->encrypt($this->sampleId);
        $this->assertNotEquals($encryptedContent, $this->sampleId);
    }

    public function testDecryptStringDoesNotReturnSameString()
    {
        $encryptedContent = $this->paramEncrypter->encrypt($this->sampleId);
        $this->assertNotNull($encryptedContent);

        $decryptedContent = $this->paramEncrypter->decrypt($encryptedContent);
        $this->assertNotEquals($decryptedContent, $encryptedContent);
    }

    public function testDecryptStringReturnsSampleId()
    {
        $encryptedContent = $this->paramEncrypter->encrypt($this->sampleId);
        $decryptedContent = $this->paramEncrypter->decrypt($encryptedContent);

        $this->assertEquals($decryptedContent, (string)$this->sampleId);
    }
}
