<?php

namespace DvsaCommonApiTest\Obfuscate;

use DvsaCommon\Obfuscate\EncryptionKey;
use DvsaCommon\Obfuscate\InvalidArgumentException;
use DvsaCommon\Obfuscate\ParamEncoder;
use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Crypt\Exception\InvalidArgumentException as CryptInvalidArgumentException;

class ParamObfuscatorTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    const ENTRY_KEY = 'entryKey';

    /** @var  ParamObfuscator $paramObfuscator */
    private $paramObfuscator;

    /** @var  ParamEncrypter $paramEncrypter */
    private $paramEncrypter;

    /** @var  ParamEncoder $paramEncoder */
    private $paramEncoder;
    /** @var  \Zend\ServiceManager\ServiceManager */
    private $serviceManager;
    /** @var  array */
    private $config;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        // Having to specify key here for security settings mainly
        // because only the API would have these defined.
        $encryptKey = 'abc123456789';

        $this->config = $this->serviceManager->get('config');
        $this->config = array_merge(
            $this->config, [
                'security' => [
                    'obfuscate' => [
                        'key' => 'abc123456789',
                        'entries' => [
                            self::ENTRY_KEY => true,
                        ]
                    ],
                ],
            ]
        );

        $this->paramEncrypter = $this->getMockBuilder(ParamEncrypter::class)
            ->setConstructorArgs([new EncryptionKey($encryptKey)])
            ->setMethods(['decrypt'])
            ->getMock();

        $this->paramEncoder   = $this->getMockBuilder(ParamEncoder::class)
            ->setMethods(['decode'])
            ->getMock();

        $this->paramObfuscator = new ParamObfuscator(
            $this->paramEncrypter,
            $this->paramEncoder,
            $this->config
        );
    }

    /**
     * See ParamEncrypterTest for tests on Encryption/Decryption.
     */
    public function testCanRetrieveInstanceOfParamEncoder()
    {
        $this->assertInstanceOf(ParamEncoder::class, $this->paramObfuscator->getParamEncoder());
    }

    /**
     * See ParamEncoderTest for tests on Encoder/Decoder.
     */
    public function testCanRetrieveInstanceOfParamEncrypter()
    {
        $this->assertInstanceOf(ParamEncrypter::class, $this->paramObfuscator->getParamEncrypter());
    }

    public function testObfuscateThenDeobfuscateReturnOriginalValue()
    {
        $this->serviceManager->setService('config', $this->config);
        $this->paramEncrypter = $this->serviceManager->get(ParamEncrypter::class);
        $this->paramObfuscator = $this->serviceManager->get(ParamObfuscator::class);

        $param              = 'unit_Value';
        $obfuscatedParam    = $this->paramObfuscator->obfuscate($param);
        $deobfuscatedParam  = $this->paramObfuscator->deobfuscate($obfuscatedParam);

        $this->assertNotFalse($obfuscatedParam);
        $this->assertEquals($deobfuscatedParam, $param);
    }

    /** @dataProvider dataProviderTestObfuscateEntry */
    public function testObfuscateEntry($config, $entryKey, $obfValue, $expect)
    {
        //  --  mock    --
        if (!empty($config)) {
            $this->config['security']['obfuscate']['entries'] = [$config['key'] => $config['value']];

            $this->paramObfuscator = new ParamObfuscator(
                $this->paramEncrypter,
                $this->paramEncoder,
                $this->config
            );
        }

        //  --  set expected exception  --
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        //  --  call    --
        $actual = $this->paramObfuscator->obfuscateEntry($entryKey, $obfValue);

        //  --  check   --
        if (!empty($expect['isResult'])) {
            $this->assertNotEmpty($actual);
        }

        if (isset($expect['isObfuscated'])) {
            $this->assertNotSame($expect['isObfuscated'], ($obfValue === $actual));
        }
    }

    public function dataProviderTestObfuscateEntry()
    {
        return [
            [
                'config'   => null,
                'entryKey' => null,
                'obfValue' => 'unit_Value',
                'expect'   => [
                    'isResult'     => true,
                    'isObfuscated' => true,
                ],
            ],
            //  --  provided entry key not specified in config   --
            [
                'config'   => null,
                'entryKey' => 'wrong_key',
                'obfValue' => null,
                'expect'   => [
                    'exception' => [
                        'class'   => InvalidArgumentException::class,
                        'message' => 'The entry key was not found in configuration file.',
                    ],
                ],
            ],
            //  --  value should not be empty   --
            [
                'config'   => null,
                'entryKey' => self::ENTRY_KEY,
                'obfValue' => '',
                'expect'   => [
                    'exception' => [
                        'class'   => InvalidArgumentException::class,
                        'message' => 'The parameter cannot be empty.',
                    ],
                ],
            ],
            //  --  value for entry key should be obfuscated    --
            [
                'config'   => null,
                'entryKey' => self::ENTRY_KEY,
                'obfValue' => 'unit_Value',
                'expect'   => [
                    'isResult'     => true,
                    'isObfuscated' => true,
                ],
            ],
            //  --  value for entry key should not be obfuscated    --
            [
                'config'   => [
                    'key'   => self::ENTRY_KEY,
                    'value' => false,
                ],
                'entryKey' => self::ENTRY_KEY,
                'obfValue' => 'unit_Value',
                'expect'   => [
                    'isResult'     => true,
                    'isObfuscated' => false,
                ],
            ],
        ];
    }


    /** @dataProvider dataProviderTestDeobfuscateEntry */
    public function testDeobfuscateEntry($config, $mocks, $params, $expect)
    {
        //  --  mock    --
        if ($mocks !== null) {
            foreach ($mocks as $mock) {
                $invocation = ArrayUtils::tryGet($mock, 'invocation', $this->once());
                $mockParams = ArrayUtils::tryGet($mock, 'params', null);

                $this->mockMethod(
                    $this->{$mock['class']}, $mock['method'], $invocation, $mock['result'], $mockParams
                );
            }
        }

        if (!empty($config)) {
            $this->config['security']['obfuscate']['entries'] = [$config['key'] => $config['value']];

            $this->paramObfuscator = new ParamObfuscator(
                $this->paramEncrypter,
                $this->paramEncoder,
                $this->config
            );
        }

        //  --  set expected exception  --
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        //  --  call    --
        $actual = XMock::invokeMethod($this->paramObfuscator, 'deobfuscateEntry', $params);

        //  --  check   --
        if (isset($expect['result'])) {
            $this->assertSame($expect['result'], $actual);
        }
    }

    public function dataProviderTestDeobfuscateEntry()
    {
        $value = 'unit_Value';
        $obfValue = 'G7dTmxgkwydYuT_waDXOd8UR-3mT5kGh';
        $decObfValue = "S?$?'X???h5?w??y??A?";

        return [
            //  --  value for entry key should not be deobfuscated    --
            [
                'config'   => [
                    'key'   => self::ENTRY_KEY,
                    'value' => false,
                ],
                'mocks'  => null,
                'params' => [
                    'entryKey' => self::ENTRY_KEY,
                    'value'    => $obfValue,
                ],
                'expect'   => [
                    'result'       => $obfValue,
                ],
            ],

            //  --  decode return null, throw exception   --
            [
                'config' => null,
                'mocks'  => [
                    [
                        'class'  => 'paramEncoder',
                        'method' => 'decode',
                        'params' => $obfValue,
                        'result' => null,
                    ],
                ],
                'params' => [
                    'entryKey' => self::ENTRY_KEY,
                    'value'    => $obfValue,
                ],
                'expect' => [
                    'exception' => [
                        'class'   => InvalidArgumentException::class,
                        'message' => "Trying to deobfuscate something that wasn't obfuscated. " .
                            "Value: '". $obfValue ."', type: string",
                    ],
                ],
            ],

            //  --  decode OK, decrypt return null, throw exception    --
            [
                'config' => null,
                'mocks'  => [
                    [
                        'class'  => 'paramEncoder',
                        'method' => 'decode',
                        'params' => $obfValue,
                        'result' => $decObfValue,
                    ],
                    [
                        'class'  => 'paramEncrypter',
                        'method' => 'decrypt',
                        'params' => $decObfValue,
                        'result' => null,
                    ],
                ],
                'params' => [
                    'entryKey' => self::ENTRY_KEY,
                    'value'    => $obfValue,
                ],
                'expect' => [
                    'exception' => [
                        'class'   => InvalidArgumentException::class,
                        'message' => "Trying to deobfuscate something that wasn't obfuscated. " .
                            "Value: '". $obfValue ."', type: string",
                    ],
                ],
            ],

            //  --  decode OK, decrypt throw exception, throw exception   --
            [
                'config' => null,
                'mocks'  => [
                    [
                        'class'  => 'paramEncoder',
                        'method' => 'decode',
                        'params' => $obfValue,
                        'result' => $decObfValue,
                    ],
                    [
                        'class'  => 'paramEncrypter',
                        'method' => 'decrypt',
                        'params' => $decObfValue,
                        'result' => new CryptInvalidArgumentException('unit error msg'),
                    ],
                ],
                'params' => [
                    'entryKey' => self::ENTRY_KEY,
                    'value'    => $obfValue,
                    'fallback' => false,
                ],
                'expect' => [
                    'exception' => [
                        'class'   => InvalidArgumentException::class,
                        'message' => 'unit error msg',
                    ],
                ],
            ],

            //  --  decode OK, decrypt OK ->  return deobfuscated value --
            [
                'config' => null,
                'mocks'  => [
                    [
                        'class'  => 'paramEncoder',
                        'method' => 'decode',
                        'params' => $obfValue,
                        'result' => $decObfValue,
                    ],
                    [
                        'class'  => 'paramEncrypter',
                        'method' => 'decrypt',
                        'params' => $decObfValue,
                        'result' => $value,
                    ],
                ],
                'params' => [
                    'entryKey' => self::ENTRY_KEY,
                    'value'    => $obfValue,
                    'fallback' => false,
                ],
                'expect' => [
                    'result' => $value,
                ],
            ],
        ];
    }
}
