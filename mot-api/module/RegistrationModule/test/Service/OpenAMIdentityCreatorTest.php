<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Service;

use Dvsa\OpenAM\Model\OpenAMNewIdentity;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommonTest\TestUtils\XMock;

/**
 * Class OpenAMIdentityCreatorTest.
 */
class OpenAMIdentityCreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OpenAMIdentityCreator
     */
    private $subject;

    public function setUp()
    {
        /** @var OpenAMClient $mockOpenAMClient */
        $mockOpenAMClient = XMock::of(OpenAMClient::class);

        /** @var OpenAMClientOptions $mockOpenAMClientOptions */
        $mockOpenAMClientOptions = XMock::of(OpenAMClientOptions::class);

        $this->subject = new OpenAMIdentityCreator(
            $mockOpenAMClient,
            $mockOpenAMClientOptions
        );
    }

    /**
     * @dataProvider dpCredential
     *
     * @param $username
     * @param $password
     * @param $firstName
     * @param $lastName
     * @param $objectClass
     * @param $expectingException
     */
    public function testCreateIdentity($username, $password, $firstName, $lastName, $objectClass, $expectingException)
    {
        if (false !== $expectingException) {
            $this->setExpectedException(\InvalidArgumentException::class, $expectingException);
        }

        $this->assertInstanceOf(
            OpenAMNewIdentity::class,
            $this->subject->createIdentity($username, $password, $firstName, $lastName, $objectClass)
        );
    }

    public function dpCredential()
    {
        $method = 'createIdentity';

        return [
            [
                '1234LAST',
                'Password',
                'First name',
                'Last name',
                null,
                false,
            ],
            [
                '',
                'Password',
                'First name',
                'Last name',
                null,
                sprintf(OpenAMIdentityCreator::EXP_EMPTY_ARG, '$username', $method),
            ],
            [
                '1234LAST',
                '',
                'First name',
                'Last name',
                null,
                sprintf(OpenAMIdentityCreator::EXP_EMPTY_ARG, '$password', $method),
            ],
            [
                '1234LAST',
                'Password',
                '',
                'Last name',
                null,
                sprintf(OpenAMIdentityCreator::EXP_EMPTY_ARG, '$firstName', $method),
            ],
            [
                '1234LAST',
                'Password',
                'First name',
                '',
                null,
                sprintf(OpenAMIdentityCreator::EXP_EMPTY_ARG, '$lastName', $method),
            ],
            [
                [],
                'Password',
                'First name',
                'Last name',
                null,
                sprintf(OpenAMIdentityCreator::EXP_NON_STRING_ARG, '$username', $method),
            ],
            [
                '1234LAST',
                new \stdClass(),
                'First name',
                'Last name',
                null,
                sprintf(OpenAMIdentityCreator::EXP_NON_STRING_ARG, '$password', $method),
            ],
            [
                '1234LAST',
                'Password',
                false,
                'Last name',
                null,
                sprintf(OpenAMIdentityCreator::EXP_NON_STRING_ARG, '$firstName', $method),
            ],
            [
                '1234LAST',
                'Password',
                'First name',
                null,
                null,
                sprintf(OpenAMIdentityCreator::EXP_NON_STRING_ARG, '$lastName', $method),
            ],
        ];
    }
}
